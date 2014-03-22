<?php
/**
 * Handler for configuration.
 *
 * @author  Iskandar Soesman <k4ndar@yahoo.com>
 * @link    http://panadaframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @since   version 1.0.0
 * @package Resources
 */
namespace Resources;

class Config
{    
    static private $config = array();
    
    static private function _cache($name)
    {    
        if( ! isset(self::$config[$name]) ) {
            $array = require APP . 'config/'.$name.'.php';
            self::$config[$name] = $array;
            return $array;
        }
        else {
            return self::$config[$name];
        }
    }
    
    static public function main()
    {
        return self::_cache('main');
    }
    
    static public function session()
    {
        return self::_cache('session');
    }
    
    static public function cache()
    {
        return self::_cache('cache');
    }
    
    static public function database()
    {
        return self::_cache('database');
    }
    
    /**
     * Handler for user defined config
     */
    public static function __callStatic( $name, $arguments = array() )
    {    
        // Does cache for this config exists?
        if( isset(self::$config[$name]) )
            return self::$config[$name];
        
        // Does the config file exists?
        try{
            if( ! file_exists( $file = APP . 'config/'.$name.'.php' ) )
                throw new RunException('Config file in '.$file.' does not exits');
        }
        catch(RunException $e){
            RunException::outputError($e->getMessage());
        }
        
        return self::_cache($name);
    }
}