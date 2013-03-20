<?php
abstract class Dfp_Datafeed_File_Locator_Adapter_Abstract implements Dfp_Datafeed_File_Locator_Adapter_Interface
{
	protected $_iterator;
	
	public function setInnerIterator(Iterator $iterator)
	{
		$this->_iterator = $iterator;
		return $this;
	}
	
	public function getInnerIterator()
	{
		return $this->_iterator;
	}
	
	public function current()
	{
		return $this->getInnerIterator()->current();
	}
	
	public function rewind()
	{
		return $this->getInnerIterator()->rewind();
	}
	
	public function key()
	{
		return $this->getInnerIterator()->key();
	}
	
	public function next()
	{
		do {
			$return = $this->getInnerIterator()->next();
		} while (!$this->accept($this->getInnerIterator()->current())); 
			
		return $return;
	}
	
	public function valid()
	{
		return $this->getInnerIterator()->valid();
	}
	
	abstract public function accept($value); 
}  