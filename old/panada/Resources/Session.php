<?php
/**
 * Panada session Handler.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license	http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

class Session
{    
    private $driver, $config;
    
    public function __construct($connection = 'default')
    {    
        $this->config = Config::session();
	$this->config = $this->config[$connection];
	$this->init();
    }
    
    /**
     * Overrider for session config option located at file app/config/session.php
     *
     * @param array $option The new option.
     * @return void
     * @since version 1.0
     */
    public function setOption( $option = array() )
    {
	$this->config = array_merge($this->config, $option);
	$this->init();
    }
    
    /**
     * Use magic method 'call' to pass user method
     * into driver method
     *
     * @param string @name
     * @param array @arguments
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->driver, $name), $arguments);
    }
    
    /**
     * Magic getter for properties
     *
     * @param string
     * @return mix
     */
    public function __get($name)
    {    
        return $this->driver->$name;
    }
    
    /**
     * Magic setter for properties
     *
     * @param string
     * @param mix
     * @return mix
     */
    public function __set($name, $value)
    {    
        $this->driver->$name = $value;
    }
    
    /**
     * Instantiate the driver class
     *
     * @return void
     * @since version 1.0
     */
    private function init()
    {
	$driverNamespace = 'Drivers\Session\\'.ucwords($this->config['driver']);
        $this->driver = new $driverNamespace($this->config);
    }
}