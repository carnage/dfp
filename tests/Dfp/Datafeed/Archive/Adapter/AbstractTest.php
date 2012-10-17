<?php
/**
 * Test class for Dfp_Datafeed_Archive
 * Generated by PHPUnit on 2011-12-09 at 10:59:43.
 *
 */
class Dfp_Datafeed_Archive_Adapter_AbstractTest extends PHPUnit_Framework_TestCase
{
    public function testGetLocation()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $this->assertNull($sut->getLocation());
    }

    public function testGetExtractPath()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $this->assertNull($sut->getExtractPath());
    }

    public function testSetLocation()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $sut->setLocation('C:\\zipfile.zip');

        $this->assertEquals('C:\\zipfile.zip', $sut->getLocation());
    }

    public function testSetExtractPath()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $sut->setExtractPath('C:\\tmp\\');

        $this->assertEquals('C:\\tmp\\', $sut->getExtractPath());
    }

    public function testSetConfig()
    {
        $config = new Zend_Config(array('location'=>'test'));
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');
        $sut->setConfig($config);

        $this->assertEquals('test', $sut->getLocation());
    }

    /**
    * @todo Implement testAddError().
    */
    public function testAddError()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');
        $sut->addError('123');
        $this->assertTrue($sut->hasErrors());
        $this->assertEquals(array('123'), $sut->getErrors());
    }

    /**
     * @todo Implement testAddErrors().
     */
    public function testAddErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');
        $sut->addErrors(array('123','456'));

        $this->assertTrue($sut->hasErrors());
        $this->assertEquals(array('123','456'), $sut->getErrors());
    }

    /**
     * @todo Implement testGetErrors().
     */
    public function testGetErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $this->assertEmpty($sut->getErrors());
    }

    /**
     * @todo Implement testHasErrors().
     */
    public function testHasErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $this->assertFalse($sut->hasErrors());
    }

    /**
     * @todo Implement testSetErrors().
     */
    public function testSetErrors()
    {
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');
        $sut->addErrors(array('123','456'));

        $sut->setErrors(array('789'));

        $this->assertEquals(array('789'), $sut->getErrors());
    }

    /**
    * @dataProvider setOptionsProvider
    */
    public function testSetOptions($var, $valid, $invalid, $message, $method)
    {
        $options[$var] = $invalid;
        $sut = $this->getMockForAbstractClass('Dfp_Datafeed_Archive_Adapter_Abstract');

        $passed = false;
        try {
            $sut->setOptions($options);
        } catch (Dfp_Datafeed_Archive_Exception $e) {
            if ($e->getMessage = $message) {
                $passed = True;
            }
        }

        $this->assertTrue($passed, 'Exception not thrown');

        $options[$var] = $valid;

        $sut->setOptions($options);

        $this->assertEquals($valid, $sut->{'get' . $method}());
    }

    public function setOptionsProvider()
    {
        return array(
            array('location', 'C:\\zipfile.zip', array(), 'Invalid location specified', 'Location'),
            array('extractPath', 'C:\\tmp\\', array(), 'Invalid extract path specified', 'ExtractPath'),
        );
    }

}