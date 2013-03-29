<?php
/**
 * What this class is for.
 * 
 * Lets say you have a database with 100000 rows in it and a feed which has 100000 rows in it and you want to syncronise
 * the two. (eg insert new rows from feed, update changed rows, delete rows no longer there)
 * 
 * This class is used to wrap the file reader eg $chunk->setInnerIterator($reader) to process the feed in batches 
 * when you have a large file to process.
 * 
 * For optimal performance you should also set an index function that will take a row from your feed file 
 * and return an identifier for the row in the db, this can then be used to select the rows in one go from the 
 * database for updating.
 * 
 * Psudo code example
 * 
 * $doctrine->query('update table set seen = 0');
 * $chunk->setInnerIterator($reader)->setLimit(100)->setIndexFunction(function($row) { return $row['ref']; });
 * foreach($chunk AS $feedRows) {
 *   $dbRows = $doctrine->query('select from table where ref in (?) Index by ref', $chunk->getIndex());
 *   foreach ($feedRows AS $row) {
 *     if (exists($dbRows[$row['ref']])) {
 *       //update row from feed
 *       $dbRows[$row['ref']]->seen = 1;
 *     } else {
 *       //insert new from feed
 *     }
 *   }
 * }
 * 
 * $doctrine->query('delete from table where seen = 0');
 *   
 * 
 * @author carnage
 *
 */

class Dfp_Datafeed_Iterator_ChunkIterator implements Iterator
{
	protected $_indexFunction;
	protected $_index;
	protected $_innerIterator;
	protected $_limit;
	protected $_data;
	protected $_key = 0;
	
	public function getIndexFunction()
	{
		return $this->_indexFunction;
	}
	
	public function setIndexFunction(Closure $function)
	{
		$this->_indexFunction = $function;
	}
	
	public function getIndex()
	{
		return $this->_index;
	}
	
	public function getLimit()
	{
		return $this->_limit;
	}
	
	public function setLimit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}
	
	public function setInnerIterator(Iterator $iterator)
	{
		$this->_innerIterator = $iterator;
		$this->rewind();
		return $this;
	}
	
	public function getInnerIterator()
	{
		return $this->_innerIterator;	
	}
	
	public function rewind()
	{
		$this->getInnerIterator()->rewind();
		$this->_getInnerData();
		$this->_key = 0;
	}
	
	public function next()
	{
		$this->_getInnerData();
		$this->_key++;
	}
	
	public function current()
	{
		return $this->_data;
	}
	
	public function key()
	{
		return $this->_key;
	}
	
	public function valid()
	{
		return !is_null($this->_data);
	}
	
	protected function _getInnerData()
	{
		$indexFunction = $this->getIndexFunction();
		if (is_callable($indexFunction) && is_object($indexFunction)) {
			$index = array();
		}
		$data = array();
		for ($i=$this->getLimit();$i>0;$i--) {
			if ($this->getInnerIterator()->valid()) {
				$datum = $this->getInnerIterator()->current();
				
				if (isset($index)) {
					$index[] = $indexFunction($datum);
				}
				
				$data[] = $datum; 
				$this->getInnerIterator()->next();
			}
		}
		
		if (count($data)) {
			$this->_data = ArrayIterator($data);
		} else {
			$this->_data = null;
		}

		if (isset($index) && count($index)) {
			$this->_index = $index;
		} else {
			$this->_index = null;
		}
	}
}