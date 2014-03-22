<?php
/**
 * Panada Memcached API Driver.
 *
 * @package	Driver
 * @subpackage	Cache
 * @author	Iskandar Soesman
 * @since	Version 0.1
 */

/**
 * EN: Makesure Memcache extension is enabled
 */
namespace Drivers\Cache;
use Resources\Interfaces as Interfaces;

class Memcached extends \Memcached implements Interfaces\Cache
{    
    private $port = 11211;
    
    public function __construct( $config )
    {
	if( ! extension_loaded('memcached') )
	    die('Memcached extension that required by Driver memcached is not available.');
	
        parent::__construct();
        
        foreach($config['server'] as $server)
	    $this->addServer($server['host'], $server['port'], $server['persistent']);
    }
    
    /**
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @param string $namespace
     * @return void
     */
    public function setValue( $key, $value, $expire = 0, $namespace = false )
    {    
	$key = $this->keyToNamespace($key, $namespace);
        return $this->set($key, $value, $expire);
    }
    
    /**
     * Cached the value if the key doesn't exists,
     * other wise will false.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @param string $namespace
     * @return void
     */
    public function addValue( $key, $value, $expire = 0, $namespace = false )
    {    
	$key = $this->keyToNamespace($key, $namespace);
	return $this->add($key, $value, $expire); 
    }
    
    /**
     * Update cache value base on the key given.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @param string $namespace
     * @return void
     */
    public function updateValue( $key, $value, $expire = 0, $namespace = false )
    {    
	$key = $this->keyToNamespace($key, $namespace);
	return $this->replace($key, $value, $expire);
    }
    
    /**
     * @param string $key
     * @param string $namespace
     * @return mix
     */
    public function getValue( $key, $namespace = false )
    {    
	$key = $this->keyToNamespace($key, $namespace);
        return $this->get($key);
    }
    
    /**
     * @param string $key
     * @param string $namespace
     * @return void
     */
    public function deleteValue( $key, $namespace = false )
    {    
	$key = $this->keyToNamespace($key, $namespace);
        return $this->delete($key);
    }
    
    /**
     * Flush all cached object.
     * @return bool
     */
    public function flushValues()
    {    
	return $this->flush();
    }
    
    /**
     * Increment numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to increment the item's value
     */
    public function incrementBy($key, $offset = 1)
    {
	return $this->increment($key, $offset);
    }
    
    /**
     * Decrement numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to decrement the item's value
     */
    public function decrementBy($key, $offset = 1)
    {
	return $this->decrement($key, $offset);
    }
    
    /**
     * Namespace usefull when we need to wildcard deleting cache object.
     *
     * @param string $namespaceKey
     * @return int Unixtimestamp
     */
    private function keyToNamespace( $key, $namespaceKey = false )
    {
	if( ! $namespaceKey )
	    return $key;
	
	if( ! $namespaceValue = $this->get($namespaceKey) ){
	    $namespaceValue = time();
	    $this->set($namespaceKey, $namespaceValue, 0);
	}
	
	return $namespaceValue.'_'.$key;
    }
}