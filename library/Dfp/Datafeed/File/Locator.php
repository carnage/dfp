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
class Dfp_Datafeed_File_Locator
{
	/**
	 * The maximum number of files to return
	 * 
	 * @var integer
	 */
	protected $_maxFiles;
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
	
	public function addAdapter($adapter)
	{
		$this->_adapters[] = $adapter;
		return $this;
	}
	
	public function addAdapters(array $adapters)
	{
		foreach ($adapters AS $adapter) {
			$this->addAdapter($adapter);
		}
		return $this;
	}
	
	public function setAdapters(array $adapters)
	{
		$this->_adapters = $adapters;
		return $this; 
	}
	
	public function getAdapters()
	{
		return $this->_adapters;
	}
	
	/**
	 * Locates a set of files based on the adapters. 
	 * 
	 * @return array
	 */
	public function locateFile()
	{
		$adapters = $this->getAdapters();
		$firstAdapter = array_shift($adapters);
		$files = $firstAdapter->getFiles($this->getBasePath());

		$adapter = array_shift($adapters);
		
		while(count($files) > $this->getMaxFiles() && !is_null($adapter)) {
			$files = $adapter->filterFiles($files);
			$adapter = array_shift($adapters);
		}
		
		return $files;
	}
}