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
 * @subpackage  File_Locator_Adapter_Interface
 * @copyright   Copyright (c) 2013 PHP Datafeed Library
 * @since       2013-02-21
 */
/**
 * Dfp_Datafeed_File_Locator_Adapter_Interface class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  File_Locator_Adapter_Interface
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2013-02-21
 */
interface Dfp_Datafeed_File_Locator_Adapter_Interface
{
	/**
	 * This method should return an array of files which pass the filter rules 
	 * from the file system path $dir
	 * 
	 * @param string $dir
	 * @return array
	 */
	public function getFiles($dir);
	
	/**
	 * This method should return an array of files which pass the filter rules 
	 * from the array of files $files
	 * 
	 * @param array $files
	 * @return array
	 */
	public function filterFiles($files);
}