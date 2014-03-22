<?php
/**
 * Properties Loader.
 *
 * @author  Iskandar Soesman <k4ndar@yahoo.com>
 * @link    http://panadaframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @since   version 1.0.0
 * @package Resources
 */
namespace Resources;

class PropertiesLoader
{    
    private
        $childNamespace,
        $classNamespace,
        $cache;
    
    public function __construct($childNamespace, $classNamespace)
    {    
        $this->childNamespace = $childNamespace;
        $this->classNamespace = $classNamespace;
        
        $this->cache = new Cache('default', 'Dummy');
    }
    
    public function __call( $name, $arguments = array() )
    {    
        $class = $this->classNamespace.'\\'.ucwords($name);
        
        if( $this->childNamespace[0] == 'Modules' && $this->classNamespace != 'Resources' ) {
            
            $file = APP.$this->childNamespace[0].'/'.$this->childNamespace[1].'/'.str_replace('\\','/',$class).'.php';
            
            // if this module has this class, lets call it
            if( file_exists($file) )
                $class = $this->childNamespace[0].'\\'.$this->childNamespace[1].'\\'.$class;
        }
        
        $cacheKey = 'AutoLoaderClass_'.$class.http_build_query($arguments);
        
        // Are this class has ben called before?
        if( $cachedObj = $this->cache->getValue($cacheKey) )
            return $cachedObj;
        
        $reflector = new \ReflectionClass($class);
        
        // Lets try this class's constructor.
        try{
            $object = $reflector->newInstanceArgs($arguments);
        }
        catch(\ReflectionException $e){
            $object = new $class;
        }
        
        // For Resources package, we dont need these following properties,
        // since it never use it, just go to the return.
        if( $this->classNamespace == 'Resources' )
            goto toReturn;
        
        $object->library    = new PropertiesLoader( $this->childNamespace, 'Libraries' );
        $object->model      = new PropertiesLoader( $this->childNamespace, 'Models' );
        $object->resource   = new PropertiesLoader( $this->childNamespace, 'Resources' );
        
        $object->Library    = clone $object->library;
        $object->libraries  = clone $object->library;
        $object->Libraries  = clone $object->library;
        $object->Model      = clone $object->model;
        $object->models     = clone $object->model;
        $object->Models     = clone $object->model;
        $object->Resources  = clone $object->resource;
        $object->resources  = clone $object->resource;
        $object->Resource   = clone $object->resource;
        
        toReturn:
        
        // Save all defined object before returned.
        $this->cache->setValue($cacheKey, $object);
        
        return $object;
    }
    
    public function __get($name)
    {
        return $this->__call($name);
    }
}