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
 * @subpackage  Manager_Operation_File_Reader
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @since       2012-11-07
 */
/**
 * Dfp_Datafeed_Manager_Operation_File_Reader class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Manager_Operation_File_Reader
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2012-11-07
 */
 
class Dfp_Datafeed_Manager_Operation_File_Reader implements Dfp_Datafeed_Manager_Operation_File_Interface
{
	public function run()
	{
		//psudo code for now. 
		
		//options should contain everything needed to instansiate the adapter. eg filepath, adaptername dialect etc
		$options = $this->getComponentOptions();
		//this bit needs to be its own method for mocking
		$component = new Dfp_Datafeed_File_Reader($options);
		
		foreach($component AS $record) {
			//it remains to be decided exactly how get callback should function.
			//perhaps a user could extend this operation to perform their specific reading tasks otherwise we could
			//employ a variety of strategies to obtain the object/method/function they want to call for each line
			call_user_func($this->getCallback(), $record);
		}

		$this->setErrors($component->getErrors());
		
		return $this->hasErrors();
	}
}