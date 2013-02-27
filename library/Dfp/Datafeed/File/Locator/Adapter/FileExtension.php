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
 * @subpackage  File_Locator_Adapter_FileExtension
 * @copyright   Copyright (c) 2013 PHP Datafeed Library
 * @since       2013-02-26
 */
/**
 * Dfp_Datafeed_File_Locator_Adapter_FileExtension class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  File_Locator_Adapter_FileExtension
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2013-02-26
 */
class Dfp_Datafeed_File_Locator_Adapter_FileExtension extends Dfp_Datafeed_File_Locator_Adapter_Abstract 
{
	/**
	 * An array of the extensions this adapter will allow
	 * 
	 * @var array
	 */
	protected $_extensions = array();
	
	/**
	 * Setter for extensions
	 * 
	 * @param array $extensions
	 * @return Dfp_Datafeed_File_Locator_Adapter_FileExtension
	 */
	public function setExtensions(array $extensions)
	{
		$this->_extensions = $extensions;
		return $this;
	}
	
	/**
	 * Getter for extensions
	 * 
	 * @return array
	 */
	public function getExtensions()
	{
		return $this->_extensions;
	}
	
	/**
	 * @see Dfp_Datafeed_File_Locator_Adapter_Abstract::setOptions()
	 */
	public function setOptions(array $options)
	{
		if (array_key_exists('extensions', $options)) {
			$this->setExtensions($options['extensions']);
			unset($options['extensions']);
		}
		
		return parent::setOptions($options);
	}
	
	/**
	 * @see Dfp_Datafeed_File_Locator_Adapter_Abstract::_filterFileList()
	 */
	protected function _filterFileList(Iterator $files)
	{
		$output = array();
		$extensions = array_map('strtolower', $this->getExtensions());
		foreach ($files AS $file) {
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if (in_array(strtolower($ext), $extensions)) {
				$output[] = $file;
			}
		}
		return $output;
	}
}