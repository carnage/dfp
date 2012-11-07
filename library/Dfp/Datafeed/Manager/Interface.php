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
 * Dfp_Datafeed_Manager_Interface interface.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Manager
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2012-11-07
 */
 
interface Dfp_Datafeed_Manager_Interface extends Dfp_Option_Interface, Dfp_Error_Interface
{
	public function setBreakOnError($break=true);
	public function getBreakOnError();
	
	public function setOperations(array $operations);
	public function getOperations();
	public function addOperation(Dfp_Datafeed_Manager_Operation_Interface $operation);
	public function addOperations(array $operations);
	
	public function process();
}