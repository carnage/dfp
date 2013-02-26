<?php
/**
 * PHP Datafeed Library
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  File_Locator
 * @copyright   Copyright (c) 2013 PHP Datafeed Library
 * @since       2013-02-21
 */
/**
 * Dfp_Datafeed_File_Locator class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  File_Locator
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2013-02-21
 */
class Dfp_Datafeed_File_Locator implements Dfp_Datafeed_File_Locator_Interface
{
	/**
	 * The maximum number of files to return
	 * 
	 * @var integer
	 */
	protected $_maxFiles;
	/**
	 * This setting determines if all the filters should be used.
	 * 
	 * @var boolean
	 */
	protected $_filterAll = false;
	/**
	 * The directory to search for files
	 * 
	 * @var string
	 */
	protected $_basePath;
	/**
	 * An array of adapters to use to look for the file
	 * 
	 * @var array
	 */
	protected $_adapters;
	
	/**
	 * @see Dfp_Option_Interface::setConfig
	 * @param Zend_Config $config
	 */
	public function setConfig(Zend_Config $config)
	{
		return $this->setOptions($config->toArray());
	}
	
	/**
	 * @see Dfp_Option_Interface::setOptions
	 * @param array $options
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setOptions(array $options)
	{
		if (array_key_exists('basePath', $options)) {
			$this->setBasePath($options['basePath']);
			unset($options['basePath']);
		}
		if (array_key_exists('maxFiles', $options)) {
			$this->setMaxFiles($options['maxFiles']);
			unset($options['maxFiles']);
		}
		if (array_key_exists('filterAll', $options)) {
			$this->setFilterAll($options['filterAll']);
			unset($options['filterAll']);
		}
		if (array_key_exists('adapters', $options) && is_array($options['adapters'])) {
			foreach ($options['adapters'] AS $adapter) {
				if ($adapter instanceof Dfp_Datafeed_File_Locator_Adapter_Interface) {
					$this->addAdapter($adapter);
				} else {
					$this->addAdapter(Dfp_Datafeed_File_Locator_Adapter_Abstract::factory($adapter));
				}
			}
			unset($options['adapter']);
		}
		
		return $this;
	}
	
	/**
	 * Getter for max files
	 * 
	 * @return integer
	 */
	public function getMaxFiles()
	{
		return $this->_maxFiles;
	}
	
	/**
	 * Setter for max files
	 * 
	 * @param integer $files
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setMaxFiles($files)
	{
		$this->_maxFiles = $files;
		return $this;
	}
	
	/**
	 * Getter for base path
	 * 
	 * @return string
	 */
	public function getBasePath()
	{
		return $this->_basePath;
	}
	
	/**
	 * Setter for base path
	 * 
	 * @param string $path
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setBasePath($path)
	{
		$this->_basePath = $path;
		return $this;
	}
	
	/**
	 * Setter for filter all.
	 * 
	 * @param boolean $filter
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setFilterAll($filter)
	{
		$this->_filterAll = $filter;
		return $this;
	}
	
	/**
	 * Getter for filter all.
	 * 
	 * @return boolean
	 */
	public function getFilterall()
	{
		return $this->_filterAll;
	}
	
	/**
	 * Adds an adapter to the stack
	 * 
	 * @param Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function addAdapter(Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter)
	{
		$this->_adapters[] = $adapter;
		return $this;
	}
	
	/**
	 * Adds multiple adapters to the stack
	 * 
	 * @param array $adapters
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function addAdapters(array $adapters)
	{
		foreach ($adapters AS $adapter) {
			$this->addAdapter($adapter);
		}
		return $this;
	}
	
	/**
	 * Sets the adapter stack
	 * 
	 * @param array $adapters
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setAdapters(array $adapters)
	{
		$this->_adapters = $adapters;
		return $this; 
	}
	
	/**
	 * Returns the adapter stack
	 * 
	 * @return array
	 */
	public function getAdapters()
	{
		return $this->_adapters;
	}
	
	/**
	 * Locates a set of files based on the adapters. 
	 * The first adapter in the stack is used to find candidate files from the file system
	 * then each additional adapter is used to filter the list. 
	 * 
	 * Filtering stops when there are less than max files left in the list 
	 * or when there are no adapters left to filter with.
	 * 
	 * If filterAll is set filtering continues until all the filters have been used
	 * 
	 * 
	 * @return array
	 */
	public function locateFile()
	{
		$adapters = $this->getAdapters();
		$firstAdapter = array_shift($adapters);
		$files = $firstAdapter->getFiles($this->getBasePath());

		$adapter = array_shift($adapters);
		
		$maxFiles = $this->getMaxFiles();
		
		while(
				!is_null($adapter) &&
				(is_null($maxFiles) || (count($files) > $maxFiles) || $this->getFilterAll())
		) {
			$files = $adapter->filterFiles($files);
			$adapter = array_shift($adapters);
		}
		
		if (!is_null($maxFiles) && (count($files) > $maxFiles)) {
			$files = array_slice($files, 0, $maxFiles);
		}
		
		return $files;
	}
}