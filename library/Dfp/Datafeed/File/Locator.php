<?php
class Dfp_Datafeed_File_Locator
{
	protected $_maxFiles;
	protected $_basePath;
	protected $_adapters;
	
	public function getMaxFiles()
	{
		return $this->_maxFiles;
	}
	
	public function setMaxFiles($files)
	{
		$this->_maxFiles = $files;
		return $this;
	}
	
	public function getBasePath()
	{
		return $this->_basePath;
	}
	
	public function setBasePath($path)
	{
		$this->_basePath = $path;
		return $this;
	}
	
	public function addAdapter($adapter)
	{
		$this->_adapters[] = $adapter;
		return $this;
	}
	
	public function addAdapters(array $adapters)
	{
		foreach ($adapters AS $adapter) {
			$this->addAdapter($adapter);
		}
		return $this;
	}
	
	public function setAdapters(array $adapters)
	{
		$this->_adapters = $adapters;
		return $this; 
	}
	
	public function getAdapters()
	{
		return $this->_adapters;
	}
	
	public function locateFile()
	{
		$adapters = $this->getAdapters();
		$firstAdapter = array_shift($adapters);
		$files = $firstAdapter->getFiles($this->getBasePath());

		$adapter = array_shift($adapters);
		
		while(count($files) > $this->getMaxFiles() && !is_null($adapter)) {
			$files = $adapter->filterFiles($files);
			$adapter = array_shift($adapters);
		}
		
		return $files;
	}
}