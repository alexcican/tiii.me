<?php
/**
 * Panada cache API.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.3
 */
namespace Resources;

class Cache
{    
    private $driver, $config;
    
    public function __construct( $connection = 'default', $specifiedDriver = false )
    {    
        $this->config = Config::cache();
        $this->config = $this->config[$connection];
        
        if($specifiedDriver)
            $this->config['driver'] = $specifiedDriver;
        
        $driverNamespace = 'Drivers\Cache\\'.ucwords($this->config['driver']);
        
        $this->driver = new $driverNamespace( $this->config );
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
     * PHP Magic method for calling a class property dinamicly
     * 
     * @param string $name
     * @return mix
     */
    public function __get($name)
    {    
        return $this->driver->$name;
    }
    
    /**
     * PHP Magic method for set a class property dinamicly
     * 
     * @param string $name
     * @param mix $value
     * @return void
     */
    public function __set($name, $value)
    {    
        $this->driver->$name = $value;
    }
    
} // End Library_cache