<?php
class Dfp_Datafeed_File_LocatorTest extends PHPUnit_Framework_TestCase
{
	public function testGetBasePath()
	{
		$sut = new Dfp_Datafeed_File_Locator();
		$this->assertempty($sut->getBasePath());
	}
	
	public function testSetBasePath()
	{
		$sut = new Dfp_Datafeed_File_Locator();
		$sut->setBasePath('/files');
		
		$this->assertEquals('/files', $sut->getBasePath());
	}
	
	public function testGetMaxFiles()
	{
		$sut = new Dfp_Datafeed_File_Locator();
		$this->assertNull($sut->getMaxFiles());
	}
	
	public function testSetMaxFiles()
	{
		$sut = new Dfp_Datafeed_File_Locator();
		$sut->setMaxFiles(10);
		
		$this->assertEquals(10, $sut->getMaxFiles());		
	}
	
	public function testGetFilterAll()
	{
		$sut = new Dfp_Datafeed_File_Locator();
		$this->assertFalse($sut->getFilterall());
	}
	
	public function testSetFilterAll()
	{
		$sut = new Dfp_Datafeed_File_Locator();
		$sut->setFilterAll(true);
		
		$this->assertTrue($sut->getFilterall());		
	}
	
	public function testAddAdapter()
	{
		
	}
	
	public function testAddAdapters()
	{
		
	}
	
	public function testSetAdapters()
	{
		
	}
	
	public function testGetAdapters()
	{
		
	}
	
	public function testSetConfig()
	{
		$sut = $this->getMock('Dfp_Datafeed_File_Locator', array('setOptions'));
		$sut->expects($this->once())->method('setOptions')->with($this->equalTo(array('x'=>'y')));
		$mockConfig = new Zend_Config(array('x'=>'y'));
		
		$sut->setConfig($mockConfig);
	}
}
