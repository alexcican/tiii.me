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
 * Makesure Memcache extension is enabled
 */
namespace Drivers\Cache;
use
    Resources\Interfaces as Interfaces,
    Resources\RunException as RunException;

class Memcache extends \Memcache implements Interfaces\Cache
{    
    private $port = 11211;
    
    /**
     * Load configuration from config file.
     * @return void
     */
    
    public function __construct( $config )
    {
	if( ! extension_loaded('memcache') )
	    throw new RunException('Memcache extension that required by Memcache Driver is not available.');
        
        foreach($config['server'] as $server)
	    $this->addServer($server['host'], $server['port'], $server['persistent']);
	
	/**
	 * If you need compression Threshold, you can uncomment this
	 */
	//$this->setCompressThreshold(20000, 0.2);
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
        return $this->set($key, $value, 0, $expire);
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
	return $this->add($key, $value, 0, $expire); 
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
	return $this->replace($key, $value, 0, $expire);
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
	    $this->set($namespaceKey, $namespaceValue, 0, 0);
	}
	
	return $namespaceValue.'_'.$key;
    }
    
}