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
		$files = new DirectoryIterator($dir);
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
} 
