<?php
/**
 * Panada Database API.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.3
 */
namespace Resources;

class Database
{    
    private $driver, $config;
    
    public function __construct( $connection = 'default' )
    {    
        $config         = Config::database();
        $this->config   = $config[$connection];
        
        $driverNamespace = 'Drivers\Database\\'.ucwords($this->config['driver']);
        
        if ( isset($this->config['pdo']) )
            if( $this->config['pdo'] )
                $driverNamespace = 'Drivers\Database\PanadaPDO';
        
        $this->driver = new $driverNamespace( $this->config, $connection );
    }
    
    /**
     * Use magic method 'call' to pass user method
     * into driver method
     *
     * @param string @name
     * @param array @arguments
     * @return mix
     */
    public function __call($name, $arguments)
    {    
        return call_user_func_array(array($this->driver, $name), $arguments);
    }
    
    /**
     * @param string @name
     * @return mix
     */
    public function __get($name)
    {    
        return $this->driver->$name;
    }
    
    /**
     *
     * @param string @name
     * @param mix @$value
     * @return void
     */
    public function __set($name, $value)
    {    
        $this->driver->$name = $value;
    }
    
}
