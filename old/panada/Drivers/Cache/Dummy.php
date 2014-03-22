<?php
/**
 * Panada Local Memory Cacher.
 * This class useful when you calling an object twice or
 * more in a single run time.
 *
 * @package	Driver
 * @subpackage	Cache
 * @author	Iskandar Soesman
 * @since	Version 0.3
 */
namespace Drivers\Cache;
use Resources\Interfaces as Interfaces;

class Dummy implements Interfaces\Cache
{    
    static private $holder = array();
    
    public function __construct()
    {
       // none
    }
    
    /**
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function setValue($key, $value, $expire = 0, $namespace = false)
    {    
        return self::_set($key, $value);
    }
    
    /**
     * Cached the value if the key doesn't exists,
     * other wise will false.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function addValue( $key, $value, $expire = 0, $namespace = false )
    {    
        return self::_get($key) ? false : self::_set($key, $value);
    }
    
    /**
     * Update cache value base on the key given.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function updateValue( $key, $value, $expire = 0, $namespace = false )
    {    
        return self::_set($key, $value);
    }
    
    /**
     * @param string $key
     * @return mix
     */
    public function getValue($key, $namespace = false)
    {    
        return self::_get($key);
    }
    
    /**
     * @param string $key
     * @return void
     */
    public function deleteValue($key, $namespace = false)
    {    
        return self::_delete($key);
    }
    
    /**
     * Flush all cached object.
     * @return bool
     */
    public function flushValues()
    {    
        return self::_flush();
    }
    
    /**
     * @param string $key
     * @return mix
     */
    public static function _get($key = false)
    {    
        if( isset(self::$holder[$key]) )
            return self::$holder[$key];
        
        return false;
    }
    
    /**
     * @param string $key
     * @param mix
     * @return void
     */
    public static function _set($key, $value)
    {    
        self::$holder[$key] = $value;
    }
    
    /**
     * @param string $key
     * @return void
     */
    public static function _delete($key)
    {    
        unset(self::$holder[$key]);
    }
    
    public static function _flush()
    {    
        unset(self::$holder);
        return true;
    }
    
    /**
     * Increment numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to increment the item's value
     */
    public function incrementBy($key, $offset = 1)
    {    
        $incr = $this->getValue($key) + $offset;
        
        $this->updateValue($key, $incr);
	
	return $incr;
    }
    
    /**
     * Decrement numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to decrement the item's value
     */
    public function decrementBy($key, $offset = 1)
    {
        $decr = $this->getValue($key) - $offset;
        
        $this->updateValue($key, $decr);
        
	return $decr;
    }
}