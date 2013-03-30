<?php
/**
 * Test class for Dfp_Datafeed_Transfer
 * Generated by PHPUnit on 2011-12-09 at 10:59:43.
 *
 */
class Dfp_Datafeed_Transfer_Adapter_AbstractTest extends PHPUnit_Framework_TestCase
{
    public function testSetConfig()
    {
        $config = new Zend_Config(array('setting'=>'value'));
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Transfer_Adapter_Abstract');
        $sut->expects($this->once())->method('setOptions')->with($this->equalTo(array('setting'=>'value')));
        $sut->setConfig($config);
    }
    
    public function testAddError()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Transfer_Adapter_Abstract');
        $sut->addError('123');
        $this->assertTrue($sut->hasErrors());
        $this->assertEquals(array('123'), $sut->getErrors());
    }
    
    public function testAddErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Transfer_Adapter_Abstract');
        $sut->addErrors(array('123','456'));
    
        $this->assertTrue($sut->hasErrors());
        $this->assertEquals(array('123','456'), $sut->getErrors());
    }
    
    public function testGetErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Transfer_Adapter_Abstract');
    
        $this->assertEmpty($sut->getErrors());
    }
    
    public function testHasErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Transfer_Adapter_Abstract');
    
        $this->assertFalse($sut->hasErrors());
    }
    
    public function testSetErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Transfer_Adapter_Abstract');
        $sut->addErrors(array('123','456'));
    
        $sut->setErrors(array('789'));
    
        $this->assertEquals(array('789'), $sut->getErrors());
    }    
    
    public function testFactoryExceptions()
    {
    	//test for requrired missing parameters
    	$options = array();
    	try {
    		$class = Dfp_Datafeed_Transfer_Adapter_Abstract::factory($options);
    	} catch (Dfp_Datafeed_Transfer_Adapter_Exception $e) {
    		$message = $e->getMessage();
    	}
    	 
    	$this->assertEquals('You must provide either the classname or the full classname' , $message);
    	
    	//test for correct classname generation with namespaces
    	$targetClass = uniqid('Ftp');
    	$options = array(
    			'classname' => $targetClass,
    			'phpNamespace'=>true,
    			'namespace'=>'Adapter',
    			'prefix'=>'Dft'
    	);
    	
    	try {
    		$class = Dfp_Datafeed_Transfer_Adapter_Abstract::factory($options);
    	} catch (Dfp_Datafeed_Transfer_Adapter_Exception $e) {
    		$message = $e->getMessage();
    	}
    	
    	$this->assertEquals('Dft\\Adapter\\' . $targetClass . ' was not found.' , $message);    	
    	
    	//test for correct response for an none existant class
    	$targetClass = uniqid('Ftp');
    	$options = array('fullClassname' => $targetClass);
    	
    	try {
    		$class = Dfp_Datafeed_Transfer_Adapter_Abstract::factory($options);
    	} catch (Dfp_Datafeed_Transfer_Adapter_Exception $e) {
    		$message = $e->getMessage();
    	}
    	
    	$this->assertEquals($targetClass . ' was not found.' , $message);       	
    	
    	//test for creating class which dosn't implement the correct interface
    	$this->getMockForAbstractClass(
    			'Dfp_Datafeed_Transfer',
    			array(),
    			$targetClass
    	);
    	 
		try {    	 
    		$class = Dfp_Datafeed_Transfer_Adapter_Abstract::factory($options);
		} catch (Dfp_Datafeed_Transfer_Adapter_Exception $e) {
			$message = $e->getMessage();
		}
		
		$this->assertEquals($targetClass . ' does not implement Dfp_Datafeed_Transfer_Adapter_Interface', $message);
    }
    
    public function testFactorySetOptions()
    {
    	$options = array('classname' => 'Stream', 'options'=>array('basepath'=>'/var/files'));
    	$class = Dfp_Datafeed_Transfer_Adapter_Abstract::factory($options);
    	
    	$this->assertEquals('/var/files', $class->getBasePath());
    }
    
    /**
     * @dataProvider factoryProvider
     */
    public function testFactory($targetClass, $options)
    {
    	//generate the expected classname in php's memory
    	$this->getMockForAbstractClass(
    		'Dfp_Datafeed_Transfer_Adapter_Abstract', 
    		array(),
    		$targetClass
    	);
    	
    	
    	$class = Dfp_Datafeed_Transfer_Adapter_Abstract::factory($options);
    	$this->assertInstanceOf($targetClass, $class);
    }
    
    public function factoryProvider()
    {
    	$classnames[] = uniqid('Ftp');
    	$classnames[] = uniqid('Ftp');
    	$classnames[] = uniqid('Ftp');
    	$classnames[] = uniqid('Ftp');
    	$classnames[] = uniqid('Ftp');
    	
    	return array(
    		array(
    			'Dfp_Datafeed_Transfer_Adapter_' . $classnames[0],
    			array('classname'=>$classnames[0])
    		),
    		array(
    			'Pfd_Datafeed_Transfer_Adapter_' . $classnames[1],
    			array('prefix'=>'Pfd', 'classname'=>$classnames[1])
    		),  
    		array(
    			'Dfp_Adapter_' . $classnames[2],
    			array('namespace'=>'Adapter', 'classname'=>$classnames[2])
    		),
    		array(
    			$classnames[3],
   				array('fullClassname'=>$classnames[3])
   			),
    	);
    }
}