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
 * @subpackage  Manager_Operation_Abstract
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @since       2012-12-17
 */
/**
 * Dfp_Datafeed_Manager class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Manager_Operation_Abstract
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2012-12-17
 */
class Dfp_Datafeed_Manager_Operation_Abstract implements Dfp_Datafeed_Manager_Operation_Interface
{
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
	
	public static function factory(array $options) 
	{
		$classBits['prefix'] = 'Dfp';
		if (array_key_exists('prefix', $options)) {
			$classBits['prefix'] = $options['prefix'];
		}
		
		$classBits['namespace'] = 'Datafeed_Manager_Operation';
		if (array_key_exists('namespace', $options)) {
			$classBits['namespace'] = $options['namespace'];
		}
		
		$classBits['classname'] = $options['classname'];
		
		$classname = implode('_', $classBits);
		
		if (!class_exists($classname)) {
			throw new Dfp_Datafeed_Manager_Operation_Exception(
				sprintf('Invalid operation classname: %s', $classname)
			);
		}
		
		$class = new $classname();
		
		if (!($class instanceof Dfp_Datafeed_Manager_Operation_Interface)) {
			throw new Dfp_Datafeed_Manager_Operation_Exception(
				sprintf('%s does not implement Dfp_Datafeed_Manager_Operation_Interface', $classname)
			);
		}
		
		if (array_key_exists('options', $options)) {
			$class->setOptions($options);
		}
		
		return $class;
	}
	
	public function setOrder($order)
	{
		$this->_order = $order;
		return $this;
	}
	
	public function getOrder()
	{
		return $this->_order;
	}
	
	public function setConfig(Zend_Config $config) 
	{
		return $this->setOptions($config->toArray());
	}
	
	public function setOptions(array $options)
	{
		if (array_key_exists('order', $options)) {
			$this->setOrder($options['order']);
			unset($options['order']);
		}
		
		return $this;
	}
} 