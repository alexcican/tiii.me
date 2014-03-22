<?php
/**
 * Interface for Cache Drivers
 *
 * @author Iskandar Soesman <k4ndar@yahoo.com>
 * @link http://panadaframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @since version 1.0.0
 * @package Core System
 */
namespace Resources\Interfaces;

interface Cache
{    
    public function setValue( $key, $value, $expire = 0, $namespace = false );
    public function addValue( $key, $value, $expire = 0, $namespace = false );
    public function updateValue( $key, $value, $expire = 0, $namespace = false );
    public function getValue( $key, $namespace = false );
    public function deleteValue( $key, $namespace = false );
    public function flushValues();
    public function incrementBy($key, $offset = 1);
    public function decrementBy($key, $offset = 1);
}