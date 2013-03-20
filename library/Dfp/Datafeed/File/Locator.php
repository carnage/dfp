<?php
class Dfp_Datafeed_File_Locator
{
	protected $_baseDirectory;
	protected $_flags;
	protected $_adapter;
	protected $_firstAdapter;
	
	public function setBaseDirectory($directory)
	{
		$this->_baseDirectory = $directory;
		return $this;
	}
	
	public function getBaseDirectory()
	{
		return $this->_baseDirectory;
	}
	
	public function setFlags($flags)
	{
		$this->_flags = $flags;
		return $this;
	}
	
	public function getFlags()
	{
		return $this->_flags;
	}
	
	public function getAdapter()
	{
		return $this->_adapter;
	}
	
	public function setAdapter(Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter)
	{
		$this->_adapter = $adapter;
		$this->_firstAdapter = $adapter;
		return $this;
	}
	
	public function addAdapter(Dfp_Datafeed_File_Locator_Adapter_Abstract $adapter)
	{
		if (!is_null($this->getAdapter())) {
			$adapter->setInnerIterator($this->getAdapter());
			$this->_adapter = $adapter;
		} else {
			$this->setAdapter($adapter);
		}
		
		return $this;
	}
	
	public function addAdapters(array $adapters)
	{
		foreach ($adapters AS $adapter) {
			$this->addAdapter($adapter);
		}
		
		return $this;
	}
	
	public function getFiles()
	{
		$filesystemIterator = new FilesystemIterator($this->getBaseDirectory(), $this->getFlags());
		if (is_null($this->_firstAdapter)) {
			throw new Exception('I need an adapter dammit!');
		}
		
		$this->_firstAdapter->setInnerIterator($filesystemIterator);
		
		return iterator_to_array($this->getAdapter());
	}
}