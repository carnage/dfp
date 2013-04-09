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
 * @subpackage  Transfer_Adapter_Ftp
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @version     $Id: Ftp.php 149 2012-07-25 11:59:54Z craig@autoweb.co.uk $
 * @since       2012-05-22
 */
/**
 * Dfp_Datafeed_Transfer_Adapter_Ftp class.
 *
 * @category    Dfp
 * @package     Datafeed
 * @subpackage  Transfer_Adapter_Ftp
 * @copyright   Copyright (c) 2012 PHP Datafeed Library
 * @author      Chris Riley <chris.riley@imhotek.net>
 * @since       2012-05-22
 */

class Dfp_Datafeed_Transfer_Adapter_Ftp extends Dfp_Datafeed_Transfer_Adapter_Abstract
{
	/**
	 * Holds the uri object used for remote connections
	 *
	 * @var Dfp_Datafeed_Transfer_Uri
	 */
	protected $_uri;	
	
    /**
     * Contains the ftp connection resource.
     *
     * @var resource
     */
    protected $_ftp;

    /**
     * Contains the timeout time in seconds.
     *
     * @var string
     */
    protected $_timeout = 90;

    /**
     * Use passive mode FTP
     * 
     * @var boolean
     */
    protected $_passive = false;
    
    /**
     * Holds the base path to use for locating files locally. Files will be sent from and saved to this location.
     *
     * @var string
     */
    protected $_basePath;

    /**
     * Contains the message from the last error caught
     *
     * @var string
     */
    protected $_error;

    /**
     * @see Dfp_Option_Interface::__construct()
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Getter for timeout
     *
     * @return string
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }

    /**
     * Getter for base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->_basePath;
    }

    public function getPassive()
    {
    	return $this->_passive;
    }
    
    /**
     * Setter for timeout
     *
     * @param int $timeout
     * @return Dfp_Datafeed_Transfer_Adapter_Ftp
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
        return $this;
    }

    /**
     * Setter for base path
     *
     * @param string $basePath
     * @return Dfp_Datafeed_Transfer_Adapter_Ftp
     */
    public function setBasePath($basePath)
    {
        $basePath  = rtrim($basePath, '/');
        $basePath  = rtrim($basePath, '\\');

        $this->_basePath = $basePath;
        return $this;
    }

    /**
     * Sets if passive (true) or active (false) should be used.
     *
     * @param boolean $passive
     * @return Dfp_Datafeed_Transfer_Adapter_Ftp
     */
    public function setPassive($passive)
    {
    	$this->_passive = (bool) $passive;
        return $this;
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
    
    /**
     * Setter for options, used to configure the object by providing a set of options as an array
     * Valid values for the array depend on the object.
     *
     * @param array $options Options
     * @throws Dfp_Datafeed_Transfer_Exception
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
        if (isset($options['passive'])) {
            if (is_bool($options['passive'])) {
                $this->setPassive($options['passive']);
            } else {
                throw new Dfp_Datafeed_Transfer_Exception('Invalid set passive flag, must be boolean.');
            }
        }
        if (isset($options['timeout'])) {
            if (is_numeric($options['timeout'])) {
                $this->setTimeout($options['timeout']);
            } else {
                throw new Dfp_Datafeed_Transfer_Exception('Invalid timeout value, must be a number.');
            }
        }
    }

    /**
     * @see Dfp_Datafeed_Transfer_Adapter_Interface::sendFile()
     */
    public function sendFile($source, $destination=null)
    {
        // Source from local file system
        $sourceUri = $this->getBasePath() . DIRECTORY_SEPARATOR . $source;

        if (is_null($destination)) {
            $destination = $source;
        }

        $this->_ftpConnect();
        
		$this->_ftpPut($destination, $sourceUri);

        return $this;
    }

    /**
     * @see Dfp_Datafeed_Transfer_Adapter_Interface::retrieveFile()
     */
    public function retrieveFile($source, $destination=null)
    {
        if (is_null($destination)) {
            $destination = $source;
        }
        // Destination to local file system
        $destUri = $this->getBasePath() . DIRECTORY_SEPARATOR . $destination;

        $this->_ftpConnect();
        
        $this->_ftpGet($destUri, $source);
        
        return $this;
    }
    
    protected function _ftpGet($dest, $source)
    {
    	/**
    	 * Here we change the error handler to catch the error message thrown by ftp_get
    	 */
    	set_error_handler(array($this, 'errorHandler'), E_WARNING);
    	$get = ftp_get($this->_ftp, $dest, $source, FTP_BINARY);
    	restore_error_handler();
    	
    	if (!$get) {
    		$message = sprintf('File GET failed for file from %s to %s', $source, $destUri);
    		$this->addError($message);
    		throw new Dfp_Datafeed_Transfer_Adapter_Exception($message);
    	}    	
    }
    
    protected function _ftpPut($destination, $source)
    {
    	/**
    	 * Here we change the error handler to catch the error message thrown by ftp_put
    	 */
    	set_error_handler(array($this, 'errorHandler'), E_WARNING);
    	$put = ftp_put($this->_getFtp(), $destination, $source, FTP_BINARY);
    	restore_error_handler();  

    	if (!$put) {
    		//$error = array_pop($this->getErrors());
    		$message = sprintf('File PUT failed for file from %s to %s', $sourceUri, $destination);
    		$this->addError($message);
    		throw new Dfp_Datafeed_Transfer_Adapter_Exception($message);
    	}    	
    }

    protected function _ftpConnect()
    {
    	if (is_resource($this->_ftp)) {
    		ftp_close($this->_ftp);
    	}
    	
    	set_error_handler(array($this, 'errorHandler'), E_WARNING);
    	$this->_ftp = ftp_connect($this->getUri()->getHost(), $this->getUri()->getPort(), $this->getTimeout());
    	restore_error_handler();   

    	if (!$this->_ftp) {
    		$message = sprintf('Conection failed to %s', $this->getUri()->getHost());
    		$this->addError($message);
    		throw new Dfp_Datafeed_Transfer_Adapter_Exception($message);
    	}

    	set_error_handler(array($this, 'errorHandler'), E_WARNING);
    	$login = ftp_login($this->_ftp, $this->getUri()->getUsername(), $this->getUri()->getPassword());
    	restore_error_handler();
    	
    	if (!$login) {
    		$message = sprintf(
    				'Login failed to %s @ %s Port: %s, Using password: %s',
    				$this->getUri()->getUsername(), $this->getUri()->getHost(), $this->getUri()->getPort(),
    				(('' != $this->getUri()->getPassword()) ? 'Yes' : 'No')
    		);
    		$this->addError($message);
    		throw new Dfp_Datafeed_Transfer_Adapter_Exception($message);
    	}  

   		ftp_pasv($ftp_stream, $this->getPassive());
    }
    
    /**
     * Catches and records the error.
     *
     * @param string $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     */
    public function errorHandler($errno, $errstr)
    {
        $errstr = trim($errstr);
        if (strstr($errstr, ':')) {
            $errstr = explode(':', $errstr);
            array_shift($errstr);
            $errstr = trim(implode('', $errstr));
        }
        $this->addError($errstr);
        return true;
    }
}