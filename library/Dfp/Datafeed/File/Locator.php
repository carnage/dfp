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
 * @since       2013-03-29
 */
/**
 * Dfp_Datafeed_File_Locator class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  File_Locator
 * @copyright   Copyright (c) 2013 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2013-03-29
 */
class Dfp_Datafeed_File_Locator implements Dfp_Option_Interface
{
	/**
	 * The base directory to search for files in.
	 * 
	 * @var string
	 */
	protected $_baseDirectory;
	
	/**
	 * Flags to pass onto the filesystem iterator
	 * 
	 * @var integer
	 */
	protected $_flags;
	
	/**
	 * The adapter used to find the files with
	 * 
	 * @var Iterator
	 */
	protected $_adapter;
	
	/**
	 * Holds a referance to the first adapter in the stack so that we can easilly
	 * give it the filesystem iterator to search.
	 * 
	 * @var Iterator
	 */
	protected $_firstAdapter;
	
	/**
	 * Constructor
	 * 
	 * @param array|Zend_Config $options
	 */
	public function __construct($options = null)
	{
		if ($options instanceof Zend_Config) {
			$this->setConfig($options);
		} elseif (is_array($options)) {
			$this->setOptions($options);
		}
	}
	
	/**
	 * Setter for base directory
	 * 
	 * @param string $directory
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setBaseDirectory($directory)
	{
		$this->_baseDirectory = $directory;
		return $this;
	}
	
	/**
	 * Getter for base directory
	 * 
	 * @return string
	 */
	public function getBaseDirectory()
	{
		return $this->_baseDirectory;
	}
	
	/**
	 * Setter for flags
	 * 
	 * @param integer $flags
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setFlags($flags)
	{
		$this->_flags = $flags;
		return $this;
	}
	
	/**
	 * Getter for flags
	 * 
	 * @return integer
	 */
	public function getFlags()
	{
		return $this->_flags;
	}
	
	/**
	 * Getter for adapter
	 * 
	 * @return Iterator
	 */
	public function getAdapter()
	{
		return $this->_adapter;
	}
	
	/**
	 * Setter for adapter; when used it replaces the current adapter stack
	 * 
	 * @param Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function setAdapter(Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter)
	{
		$this->_adapter = $adapter;
		$this->_firstAdapter = $adapter;
		return $this;
	}
	
	/**
	 * Adds a new adapter into the stack
	 * 
	 * @param Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter
	 * @return Dfp_Datafeed_File_Locator
	 */
	public function addAdapter(Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter)
	{
		if (!is_null($this->getAdapter())) {
			$adapter->setInnerIterator($this->getAdapter());
			$this->_adapter = $adapter;
		} else {
			$this->setAdapter($adapter);
		}
		
		return $this;
	}
	
	/**
	 * Adds multiple adapters into the stack
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
	 * Method to get a list of possible file matches based on the adapters used.
	 * 
	 * @return array of file paths
	 */
	public function getFiles()
	{
		$filesystemIterator = new FilesystemIterator($this->getBaseDirectory(), $this->getFlags());
		if (!is_null($this->_firstAdapter)) {
			$this->_firstAdapter->setInnerIterator($filesystemIterator);
			$adapter = $this->getAdapter();
		} else {
			$adapter = $filesystemIterator;
		}
		
		return iterator_to_array($adapter);
	}
	
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
	 */
	public function setOptions(array $options)
	{
		if (array_key_exists('baseDirectory', $options)) { 
			$this->setBaseDirectory($options['baseDirectory']);
			unset($options['baseDirectory']);
		}
		
		if (array_key_exists('flags', $options)) { 
			$this->setBaseDirectory($options['flags']);
			unset($options['flags']);
		} 
	}
}