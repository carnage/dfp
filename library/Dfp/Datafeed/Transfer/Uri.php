<?php
class Dfp_Datafeed_Transfer_Uri implements Dfp_Option_Interface
{
	/**
	 * Holds the schema type to connect with (should be ftp or ftps)
	 *
	 * @var string
	 */
	protected $_schema;
	
	/**
	 * Holds the hostname of the server to connect to
	 *
	 * @var string
	 */
	protected $_host;
	
	/**
	 * Holds the port to connect to the server on
	 *
	 * @var string
	 */
	protected $_port;
	
	/**
	 * Holds the username to connect to the server with
	 *
	 * @var string
	 */
	protected $_username;
	
	/**
	 * Holds the password to use when connecting to the server
	 *
	 * @var string
	 */
	protected $_password;
	
	/**
	 * Holds the path to use for constructing the uri
	 *
	 * @var string
	 */
	protected $_path;
	
	/**
	 * Holds the filename to use for constructing the uri
	 * 
	 * @var string
	 */
	protected $_filename;
	
	/**
	 * Getter for schema
	 *
	 * @return string
	 */
	public function getSchema()
	{
		return $this->_schema;
	}
	
	/**
	 * Getter for host
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->_host;
	}
	
	/**
	 * Getter for port
	 *
	 * @return string
	 */
	public function getPort()
	{
		return $this->_port;
	}
	
	/**
	 * Getter for username
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->_username;
	}
	
	/**
	 * Getter for password
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->_password;
	}
	
	/**
	 * Getter for path
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->_path;
	}
	
	/**
	 * Getter for filename
	 * 
	 * @return string
	 */
	public function getFilename()
	{
		return $this->_filename;
	}
	
	/**
	 * Setter for schema
	 *
	 * @param string $schema
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setSchema($schema)
	{
		$this->_schema = $schema;
		return $this;
	}
	
	/**
	 * Setter for host
	 *
	 * @param string $host
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setHost($host)
	{
		$this->_host = $host;
		return $this;
	}
	
	/**
	 * Setter for port
	 *
	 * @param string $port
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setPort($port)
	{
		$this->_port = $port;
		return $this;
	}
	
	/**
	 * Setter for username
	 *
	 * @param string $username
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setUsername($username)
	{
		$this->_username = $username;
		return $this;
	}
	
	/**
	 * Setter for password
	 *
	 * @param string $password
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setPassword($password)
	{
		$this->_password = $password;
		return $this;
	}	
	
	/**
	 * Setter for path
	 *
	 * @param string $path
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setPath($path)
	{
		$this->_path = $path;
		return $this;
	}	
	
	/**
	 * Setter for filename
	 * 
	 * @param string $filename
	 * @return Dfp_Datafeed_Transfer_Uri
	 */
	public function setFilename($filename)
	{
		$this->_filename = $filename;
		return $this;
	}
	
	/**
	 * @see Dfp_Option_Interface::__construct()
	 */
	public function __construct($options = null)
	{
		if ($options instanceof Zend_Config) {
			$this->setConfig($options);
		} elseif (is_array($options)) {
			$this->setOptions($options);
		} elseif (!is_null($options)) {
			throw new Dfp_Datafeed_Transfer_Adapter_Exception('Invalid parameter to constructor');
		}
	}	
	
	/**
	 * Constructs a remote uri based on the adapter settings.
	 *
	 * @throws Dfp_Datafeed_Transfer_Exception
	 * @return string
	 */
	public function __toString()
	{
		$uri = '';

		if (!is_null($this->getSchema())) {
			$uri = $this->getSchema() . '://';
		}		
		
		if (!is_null($this->getHost())) {
			if (is_null($this->getSchema())) {
				return '';
				throw new Dfp_Datafeed_Transfer_Exception(
					'Remote locations must specify a schema. (How do you want me to connect to your server?)'
				);
			}
			
			if (!is_null($this->getUsername())) {
				$uri .= $this->getUsername();
				if (!is_null($this->getPassword())) {
					$uri .= ':' . $this->getPassword();
				}
				$uri .= '@';
			}			
			
			$uri .= $this->getHost();
	
			if (!is_null($this->getPort())) {
				$uri .= ':' . $this->getPort();
			}
		}
	
		if (!is_null($this->getPath())) {
			if ($uri != '') {
				$uri .= '/';
			}
			$uri .= rtrim($this->getPath(), '\\/');
		}

		if (!is_null($this->getFilename())) {
			if ($uri != '') {
				$uri .= '/';
			}
			$uri .= $this->getFilename();
		}
		
		return $uri;
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
	
		if (isset($options['schema'])) {
			if (is_string($options['schema'])) {
				$this->setSchema($options['schema']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Schema');
			}
		}
		if (isset($options['host'])) {
			if (is_string($options['host'])) {
				$this->setHost($options['host']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Host');
			}
		}
		if (isset($options['port'])) {
			if (is_numeric($options['port'])) {
				$this->setPort($options['port']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Port');
			}
		}
		if (isset($options['username'])) {
			if (is_string($options['username'])) {
				$this->setUsername($options['username']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Username');
			}
		}
		if (isset($options['password'])) {
			if (is_string($options['password'])) {
				$this->setPassword($options['password']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Password');
			}
		}
		if (isset($options['path'])) {
			if (is_string($options['path'])) {
				$this->setPath($options['path']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Path');
			}
		}
		if (isset($options['filename'])) {
			if (is_string($options['filename'])) {
				$this->setFilename($options['filename']);
			} else {
				throw new Dfp_Datafeed_Transfer_Exception('Invalid Filename');
			}
		}		
	}

	/**
	 * @see Dfp_Option_Interface::setConfig()
	 * @return Dfp_Datafeed_Transfer_Adapter_Abstract
	 */
	public function setConfig(Zend_Config $config)
	{
		return $this->setOptions($config->toArray());
	}	
}