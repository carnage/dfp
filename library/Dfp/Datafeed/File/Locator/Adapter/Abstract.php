<?php
abstract class Dfp_Datafeed_File_Locator_Adapter_Abstract implements Dfp_Datafeed_File_Locator_Adapter_Interface
{
	protected $_iterator;
	
	public function setInnerIterator(Iterator $iterator)
	{
		$this->_iterator = $iterator;
		$this->rewind();
		return $this;
	}
	
	public function getInnerIterator()
	{
		return $this->_iterator;
	}
	
	public function current()
	{
		if (!$this->accept()) {
			$this->next();
		}		
		return $this->getInnerIterator()->current();
	}
	
	public function rewind()
	{
		$this->getInnerIterator()->rewind();
		if (!$this->accept()) {
			$this->next();
		}
	}
	
	public function key()
	{
		if (!$this->accept()) {
			$this->next();
		}		
		return $this->getInnerIterator()->key();
	}
	
	public function next()
	{
		do {
			$return = $this->getInnerIterator()->next();
		} while (!$this->accept()); 
			
		return $return;
	}
	
	public function valid()
	{		
		if (!$this->accept()) {
			$this->next();
		}
		return $this->getInnerIterator()->valid();
	}
	
	abstract public function accept(); 
}  