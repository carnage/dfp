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
	protected $_operations = array();
	protected $_breakOnError = false;

	/**
	 * @see Dfp_Option_Interface::__construct()
	 */
	public function __construct($options = null)
	{
		if ($options instanceof Zend_Config) {
			$this->setConfig($options);
		} elseif (is_array($options)) {
			$this->setOptions($options);
		} elseif (!is_null($options)) {
			throw new Dfp_Datafeed_Transfer_Exception('Invalid parameter to constructor');
		}
	}	
	
	public function setBreakOnError($break=true)
	{
		$this->_breakOnError = $break;
		return $this;
	}
	
	public function getBreakOnError()
	{
		return $this->_breakOnError;
	}
	
	public function addOperation(Dfp_Datafeed_Manager_Operation_Interface $operation)
	{
		$this->_operations[] = $operation;
		return $this;
	}
	
	public function addOperations($operations)
	{
		foreach ($operations as $operation) {
			$this->addOperation($operation);
		}
		
		return $this;
	}
	
	public function setOperations($operations)
	{
		$this->_operations = $operations;
		return $this;
	}
	
	public function getOperations()
	{
		return $this->_operations;
	}
	
	public function setConfig(Zend_Config $config)
	{
		$this->setOptions($config->toArray());
	}
	
	public function setOptions(array $options)
	{
		if (array_key_exists('breakOnError')) {
			$this->setBreakOnError($options['breakOnError']);
		}
		
		if (array_key_exists('operations', $options) && is_array($options['operations'])) {
			foreach ($options['operations'] AS $operation) {
				if ($operation instanceof Dfp_Datafeed_Manager_Operation_Interface) {
					$this->addOperation($operation);
				} else {
					$this->addOperation(Dfp_Datafeed_Manager_Operation_Abstract::factory($operation));
				}
			}			
		}
	}
}