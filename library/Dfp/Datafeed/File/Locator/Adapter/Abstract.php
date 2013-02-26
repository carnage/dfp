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
 * @subpackage  File_Locator_Adapter_Abstract
 * @copyright   Copyright (c) 2013 PHP Datafeed Library
 * @since       2013-02-21
 */
/**
 * Dfp_Datafeed_File_Locator_Adapter_Abstract class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  File_Locator_Adapter_Abstract
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2013-02-21
 */
abstract class Dfp_Datafeed_File_Locator_Adapter_Abstract implements Dfp_Datafeed_File_Locator_Adapter_Interface
{
	/**
	 * @see Dfp_Datafeed_File_Locator_Adapter_Interface::getFiles
	 */
	public function getFiles($dir)
	{
		$files = new FilesystemIterator($dir, FilesystemIterator::CURRENT_AS_PATHNAME|FilesystemIterator::SKIP_DOTS);
		return $this->_filterFileList($files);
	}

	/**
	 * @see Dfp_Datafeed_File_Locator_Adapter_Interface::filterFiles
	 */	
	public function filterFiles($files)
	{
		$files = new ArrayIterator($files);
		return $this->_filterFileList($files);
	}
	
	/**
	 * This method should be implemented by child classes to filter the list of files based on the 
	 * adapters specific rules
	 * 
	 * @param Iterator $files
	 * @return array
	 */
	abstract protected function _filterFileList(Iterator $files);
	
	/**
	 * @see Dfp_Option_Interface::setConfig
	 * @param Zend_Config $config
	 */
	public function setConfig(Zend_Config $config) 
	{
		return $this->setOptions($config);
	}
	/**
	 * @see Dfp_Option_Interface::setOptions
	 * @param array $options
	 * @return Dfp_Datafeed_File_Locator_Adapter_Abstract
	 */	
	public function setOptions(array $options)
	{
		return $this;
	}
	
	/**
	 * Factory method for creating an adapter. Valid array keys are as follows:
	 *
	 *  fullClassname    The full class name to create
	 *  prefix           The first part of the adapter namespace (defaults to Dfp)
	 *  namespace        The remainder of the adapter namespace (defaults to Datafeed_File_Locator_Adapter)
	 *  classname        The classname to create
	 *  phpNamespace     If set to true, the classname generated will use \ as a separator between parts instead of _
	 *  options          An array of options to pass into the new class
	 *
	 * Fullclassname or classname are required parameters, the class must implement
	 * Dfp_Datafeed_File_Locator_Adapter_Interface or an exception will be thrown
	 *
	 * @param array $options
	 * @throws Dfp_Datafeed_File_Locator_Adapter_Exception
	 * @return Dfp_Datafeed_File_Locator_Adapter_Interface
	 */
	public static function factory(array $options)
	{
		if (array_key_exists('fullClassname', $options)) {
			$classname = $options['fullClassname'];
		} elseif (array_key_exists('classname', $options)) {
			$classBits['prefix'] = 'Dfp';
			if (array_key_exists('prefix', $options)) {
				$classBits['prefix'] = $options['prefix'];
			}
	
			$classBits['namespace'] = 'Datafeed_File_Locator_Adapter';
			if (array_key_exists('namespace', $options)) {
				$classBits['namespace'] = $options['namespace'];
			}
	
			$classBits['classname'] = $options['classname'];
	
			$separator = '_';
			if (array_key_exists('phpNamespace', $options) && $options['phpNamespace']) {
				$separator = '\\';
			}
	
			$classname = implode($separator, $classBits);
		} else {
			throw new Dfp_Datafeed_File_Locator_Adapter_Exception(
					'You must provide either the classname or the full classname'
			);
		}
		 
		if (!@class_exists($classname)) {
			throw new Dfp_Datafeed_File_Locator_Adapter_Exception(
					sprintf('%s was not found.', $classname)
			);
		}
		 
		$class = new $classname();
		 
		if (!($class instanceof Dfp_Datafeed_File_Locator_Adapter_Interface)) {
			throw new Dfp_Datafeed_File_Locator_Adapter_Exception(
					sprintf('%s does not implement Dfp_Datafeed_Transfer_Adapter_Interface', $classname)
			);
		}
		 
		if (array_key_exists('options', $options)) {
			$class->setOptions($options['options']);
		}
		 
		return $class;
	}	
} 
