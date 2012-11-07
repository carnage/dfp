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
 * @subpackage  Manager
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @since       2012-11-07
 */
/**
 * Dfp_Datafeed_Manager class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Manager
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2012-11-07
 */
class Dfp_Datafeed_Manager implements Dfp_Datafeed_Manager_Interface 
{
	protected $_breakOnError = false;
	
	public function setBreakOnError($break=true)
	{
		$this->_breakOnError = $break;
		return $this;
	}
	
	public function getBreakOnError()
	{
		return $this->_breakOnError;
	}

	
}