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
 * @subpackage  Manager_Operation_Transfer_Download
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @since       2012-11-07
 */
/**
 * Dfp_Datafeed_Manager_Operation_Interface interface.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Manager_Operation_Transfer_Download
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2012-11-07
 */
 
class Dfp_Datafeed_Manager_Operation_Transfer_Download implements Dfp_Datafeed_Manager_Operation_Transfer_Interface
{
	public function run()
	{
		//psudo code for now. this bit needs to be its own method for mocking
		
		//options should contain everything needed to instansiate the adapter. eg name, namespace connection options
		$options = $this->getComponentOptions();
		$component = new Dfp_Datafeed_Transfer($options);
		
		$component->retrieveFile($this->getSource(), $this->getDestination());
		
		$this->setErrors($component->getErrors());
		
		return $this->hasErrors();
	}
}