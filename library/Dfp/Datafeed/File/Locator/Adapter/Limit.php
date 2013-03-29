<?php
class Dfp_Datafeed_File_Locator_Adapter_Limit extends Dfp_Datafeed_File_Locator_Adapter_Abstract
{
	protected $_count = 0;
	protected $_limit = 1;
	
	public function setLimit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}
	
	public function getLimit()
	{
		return $this->_limit;
	}
	
	public function accept() 
	{
		if ($this->_count <= $this->getLimit()) {
			return true;
		}
		
		return false;
	}
	
	public function rewind()
	{
		$this->_count = 0;
		return parent::rewind();
	}
	
	public function next()
	{
		$this->_count++;
		return parent::next();
	}
}