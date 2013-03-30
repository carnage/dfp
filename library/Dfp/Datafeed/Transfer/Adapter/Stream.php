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
 * @subpackage  Transfer_Adapter_Stream
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @version     $Id$
 * @since       2011-12-09
 */
/**
 * Dfp_Datafeed_Transfer_Adapter_Stream class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Transfer_Adapter_Stream
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2011-12-09
 */

class Dfp_Datafeed_Transfer_Adapter_Stream extends Dfp_Datafeed_Transfer_Adapter_Abstract
{
    /**
     * Holds the uri object used for remote connections
     *
     * @var Dfp_Datafeed_Transfer_Uri
     */
    protected $_uri;

    /**
     * Holds the base path to use for locating files locally. Files will be sent from and saved to this location.
     *
     * @var string
     */
    protected $_basePath;

    /**
     * Getter for base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->_basePath;
    }

    /**
     * Setter for base path
     *
     * @param string $basePath
     * @return Dfp_Datafeed_Transfer_Adapter_Stream
     */
    public function setBasePath($basePath)
    {
        $basePath  = rtrim($basePath, '/');
        $basePath  = rtrim($basePath, '\\');
        //$basePath .= DIRECTORY_SEPARATOR;

        $this->_basePath = $basePath;
        return $this;
    }

    /**
     * Setter for options, used to configure the object by providing a set of options as an array
     * Valid values for the array depend on the object.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $options = array_change_key_case($options);

        if (isset($options['uri'])) {
        	if ($options['uri'] instanceof Dfp_Datafeed_Transfer_Uri) {
        		$this->setUri($options['uri']);
        	} elseif (is_array($options['uri'])) {
        		$this->getUri()->setOptions($options['uri']);
        	} else {
        		throw new Dfp_Datafeed_Transfer_Exception('Invalid value for uri option');
        	}
        	unset($options['uri']);
        }
        
        if (isset($options['basepath'])) {
            if (is_string($options['basepath'])) {
                $this->setBasePath($options['basepath']);
            } else {
                throw new Dfp_Datafeed_Transfer_Exception('Invalid Basepath');
            }
        }
    }

    /**
     * Copys a file from source to destination
     *
     * @param string $sourceUri
     * @param string $destUri
     */
    protected function _copyFile($sourceUri, $destUri)
    {
        $sourceH = fopen($sourceUri, 'rb');
        $destH = fopen($destUri, 'wb');

        stream_copy_to_stream($sourceH, $destH);
    }

    /**
     * @see Dfp_Datafeed_Transfer_Adapter_Interface::sendFile()
     */
    public function sendFile($source, $destination=null)
    {
        $sourceUri = $this->getBasePath() . DIRECTORY_SEPARATOR . $source;

        if (is_null($destination)) {
            $destination = $source;
        }
        $destUri = $this->getUri() . '/' . $destination;

        $this->_copyFile($sourceUri, $destUri);

    }

    /**
     * @see Dfp_Datafeed_Transfer_Adapter_Interface::retrieveFile()
     */
    public function retrieveFile($source, $destination=null)
    {
        $sourceUri = $this->getUri() . '/' . $source;

        if (is_null($destination)) {
            $destination = $source;
        }
        $destUri = $this->getBasePath() . DIRECTORY_SEPARATOR . $destination;

        $this->_copyFile($sourceUri, $destUri);
    }

    /**
     * Returns the uri object
     *
     * @throws Dfp_Datafeed_Transfer_Exception
     * @return Dfp_Datafeed_Transfer_Uri
     */
    public function getUri()
    {
    	if (is_null($this->_uri)) {
    		$this->_uri = new Dfp_Datafeed_Transfer_Uri();
    	}
        return $this->_uri;
    }
    
    /**
     * Setter for uri
     * 
     * @param Dfp_Datafeed_Transfer_Uri $uri
     * @return Dfp_Datafeed_Transfer_Adapter_Stream
     */
    public function setUri(Dfp_Datafeed_Transfer_Uri $uri)
    {
    	$this->_uri = $uri;
    	return $this;
    }
}