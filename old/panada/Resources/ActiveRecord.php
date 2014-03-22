<?php
/**
 * Panada Active Record API.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.3
 */
namespace Resources;

class ActiveRecord {
    
    // Define the constants for db relations.
    const
        BELONGS_TO = 1,
        HAS_ONE = 2,
        HAS_MANY = 3,
        MANY_MANY = 4;
    
    protected
        $table,
        $connection = 'default',
        $setInstantiateClass = false;
    
    private
        $db,
        $fields = array(),
        $condition = array(),
        $limit = null,
        $offset = null,
        $select = '*',
        $orderBy = null,
        $order = null,
        $groupBy = array(),
        $modulPrefix = null;
    
    public
        $primaryKey = 'id';
    
    public function __construct(){
        
        $this->cache = new Cache('default', 'Dummy');
        
        // get passed arguments
        $args = func_get_args();
        
        // New data that willing to save.
        $newData = array();
        
        // If first argument is set, it could be string or array data type.
        if( isset($args[0]) && ! empty($args[0]) ){
            
            if( is_array($args[0]) )
                $newData = $args[0];
            else
                $this->connection = $args[0];
            
            // If second argument are set, its a db connection name.
            if( isset($args[1]) )
                $this->connection = $args[1];
        }
        
        $getClass           = get_class($this);
        $splitedClassName   = explode('\\', $getClass);
        $childClassName     = end($splitedClassName);
        
        // Get table name from model class name.
        $this->table    = strtolower( $childClassName );
        
        // Initeate db connection
        $this->db       = new Database($this->connection);
        
        // We'll gonna save new data if $newData not empty.
        if( ! empty($newData) ){
            
            $this->fields = $newData;
            return $this->save();
        }
        
        if( $relations = $this->relations() ){
            
            $childClassName = 'Models\\'.$childClassName;
            
            if($splitedClassName[0] == 'Modules'){
                $childClassName = $getClass;
                $this->modulPrefix = $splitedClassName[0].'\\'.$splitedClassName[1].'\\';
            }
            
            foreach( $relations as $relations ){
                if( $relations[0] == 1 || $relations[0] == 4 ){
                    $this->setInstantiateClass = $childClassName;
                }
            }
        }
    }
    
    /**
     * Return the fields and the value for insert
     * to db
     *
     * @return array
     */
    private function getFields(){
        
        if( empty($this->fields) ){
            
            $this->fields = get_object_vars($this);
            
            unset(
                $this->fields['table'],
                $this->fields['condition'],
                $this->fields['limit'],
                $this->fields['offset'],
                $this->fields['select'],
                $this->fields['orderBy'],
                $this->fields['order'],
                $this->fields['groupBy'],
                $this->fields['classVars'],
                $this->fields['connection'],
                $this->fields['fields'],
                $this->fields['db'],
                $this->fields['primaryKey'],
                $this->fields['setInstantiateClass'],
                $this->fields['cache'],
                $this->fields['modulPrefix']
            );
        }
        
        return ! empty($this->fields) ? $this->fields : array();
    }
    
    public function from($tables){
        
        if( is_string($tables) ){
            $this->table = array($this->table, $tables);
            return;
        }
        
        if( is_array($tables) ){
            $tables[] = $this->table;
            $this->table = $tables;
            return;
        }
    }
    
    /**
     * Saving new record to db
     *
     * @return booelan
     */
    public function save(){
        
        $primaryKey = $this->primaryKey;
        
        if( isset($this->$primaryKey) ){
            $return = $this->db->update($this->table, $this->getFields(), array($this->primaryKey => $this->$primaryKey));
            $this->fields = array();
            return $return;
        }
        
        if( $this->db->insert( $this->table, $this->getFields() ) ){
            $insert_id = $this->db->insertId();
            $this->fields = array();
            return $insert_id;
        }
        
        return false;
    }
    
    /**
     * Get records from db
     *
     * @param array $where
     * @param int $limit
     * @return object if true else false
     */
    public function get(){
        
        $args = func_get_args();
        $total = count($args);
        $cacheKey = 'select' . $this->select.$this->table;
        
        $this->db->select( $this->select )->from( $this->table );
        
        if($this->setInstantiateClass){
            $this->db->instantiateClass = $this->setInstantiateClass;
        }
        
        // A condition where primary key used for condition. It instead only 1 row result.
        if( $total == 1 ){
            
            $cacheKey .= $this->primaryKey . '=' . $args[0];
            $cacheKey = md5($cacheKey);
            
            if( $cached = $this->cache->getValue($cacheKey) )
                return $cached;
            
            if( ! $return = $this->db->where($this->primaryKey, '=', $args[0])->getOne() )
                return false;
            
            foreach( get_object_vars($return) as $key => $val )
                $this->$key = $val;
            
            $this->cache->setValue($cacheKey, $return);
            
            $this->setInstantiateClass = false;
            
            return $return;
        }
        
        // Condition for IN criteria.
        if( $total > 1 ){
            
            $cacheKey .= $this->primaryKey . 'IN' . http_build_query($args);
            $cacheKey = md5($cacheKey);
            
            if( $cached = $this->cache->getValue($cacheKey) )
                return $cached;
            
            $return = $this->db->where($this->primaryKey, 'IN', $args)->getAll();
            
            $this->cache->setValue($cacheKey, $return);
            
            $this->setInstantiateClass = false;
            
            return $return;
        }
        
        // Its time for user defined condition implementation.
        if( ! empty($this->condition) ){
            foreach($this->condition as $condition){
                $cacheKey .= $condition[0].$condition[1].$condition[2].$condition[3];
                $this->db->where($condition[0], $condition[1], $condition[2], $condition[3]);
            }
            
            unset($this->condition);
        }
        
        if( ! empty($this->groupBy) ){
            $cacheKey .= http_build_query($this->groupBy);
            call_user_func_array(array($this->db, 'groupBy'), $this->groupBy);
        }
        
        // Set order if user defined it
        if( ! is_null($this->orderBy) ){
            $cacheKey .= $this->orderBy.$this->order;
            $this->db->orderBy($this->orderBy, $this->order);
        }
        
        if( ! is_null($this->limit) ){
            $cacheKey .= $this->limit.$this->offset;
            $this->db->limit($this->limit, $this->offset);
        }
        
        $cacheKey = md5($cacheKey);
        
        if( $cached = $this->cache->getValue( $cacheKey ) )
            return $cached;
        
        $return = $this->db->getAll();
        
        $this->cache->setValue($cacheKey, $return);
        
        $this->setInstantiateClass = false;
        
        return $return;
    }
    
    /**
     * Delete record base on $args or $this->condition var
     * Criteria
     *
     * @param mix $args
     * @return boolean
     */
    public function delete( $args = null ){
        
        if( ! empty($this->condition) ){
            
            foreach($this->condition as $condition)
                $this->db->where($condition[0], $condition[1], $condition[2], $condition[3]);
            
            unset($this->condition);
            $condition = null;
        }
        
        else if( is_array($args) )
            $condition = $args;
        
        else if( ! is_null($args) )
            $condition = array( $this->primaryKey => $args );
        
        return $this->db->delete($this->table, $condition); 
    }
    
    /**
     * Update recored without assigning the values
     * into class properties.
     *
     * @param mix $args
     * @return boolean
     */
    public function update( $args = null ){
        
        if( ! empty($this->condition) ){
            
            foreach($this->condition as $condition)
                $this->db->where($condition[0], $condition[1], $condition[2], $condition[3]);
            
            unset($this->condition);
            $condition = null;
        }
        
        else if( is_array($args) )
            $condition = $args;
        
        else if( ! is_null($args) )
            $condition = array( $this->primaryKey => $args );
        
        return $this->db->update(
                            $this->table,
                            $this->getFields(),
                            $condition
                        );
    }
    
    /**
     * Set condition.
     *
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param mix $separator
     * @return object
     */
    public function condition( $column, $operator, $value, $separator = false ){
        
        $args = array($column, $operator, $value, $separator);
        $this->condition[] = $args;
        return $this;
    }
    
    /**
     * Short the results.
     *
     * @param string $column
     * @param string $order ASC | DESC
     * @return object
     */
    public function order($column, $order = null){
	
	$this->orderBy = $column;
	$this->order = $order;
        return $this;
    }
    
    /**
     * Select certain column
     *
     * @param string | array $select
     * @return object
     */
    public function select(){
        
        $select = func_get_args();
        
        if( empty($select) )
            $select = '*';
            
        $this->select = $select;
        return $this;
    }
    
    /**
     * Limit the results
     *
     * @param int $limit
     * @param int $offset
     * @return object
     */
    public function limit($limit, $offset = null){
        
        $this->limit = $limit;
	$this->offset = $offset;
        return $this;
    }
    
    /**
     * Group the results
     * 
     * @param string $column1, $column2 etc ...
     * @return object
     */
    public function group(){
        
        $this->groupBy = func_get_args();
        return $this;
    }
    
    /**
     * Dynamic finder method handler
     *
     * @param string $name Method name
     * @param array $arguments Method arguments
     */
    public function __call( $name, $arguments = array() ){
        
        $cacheKey = 'select' . $this->select.$this->table;
        $this->db->select( $this->select )->from($this->table);
        
        if($name == 'first'){
            $cacheKey .= $this->primaryKey.'ASC';
            return $this->db->orderBy($this->primaryKey, 'ASC')->limit(1)->getOne();
        }
        
        if($name == 'last'){
            $cacheKey .= $this->primaryKey.'DESC';
            return $this->db->orderBy($this->primaryKey, 'DESC')->limit(1)->getOne();
        }
        
        $splitedName = substr( $name, 5, strlen($name) );
        
        if( $splitedName ){
            
            try{
                if( empty($arguments) )
                    throw new RunException('getBy<b>'.$splitedName.'</b>() in Active Record method expects 1 parameter and you dont given anything yet.');
            }
            catch(RunException $e){
                $arr = $e->getTrace();
                RunException::outputError($e->getMessage(), $arr[1]['file'], $arr[1]['line']);
            }
            
            $cacheKey .= $splitedName . '=' . $arguments[0];
            $this->db->where($splitedName, '=', $arguments[0]);
            
            if( ! is_null($this->limit) ){
                $cacheKey .= $this->limit.$this->offset;
                $this->db->limit($this->limit, $this->offset);
            }
            
            if($this->setInstantiateClass)
                $this->db->instantiateClass = $this->setInstantiateClass;
            
            $cacheKey = md5($cacheKey);
            
            if( $cached = $this->cache->getValue( $cacheKey ) )
                return $cached;
        
            $results = $this->db->getAll();
            $this->setInstantiateClass = false;
            
            if( count($results) == 1 ){
                
                $pk = $this->primaryKey;
                $this->$pk = $results[0]->$pk;
                
                $this->cache->setValue($cacheKey, $results[0]);
                
                return $results[0];
            }
            
            $this->cache->setValue($cacheKey, $results);
            return $results;
            
        }
    }
    
    /**
     * overrided method for relations scheme
     */
    public function relations(){
        
        return false;
    }
    
    /**
     * Magic method for lazy call relations.
     *
     * @param string $name Property name
     * @return mix
     */
    public function __get( $name = false ){
        
        if( ! $name )
            return false;
        
        if( ! $relations = $this->relations() )
            return false;
        
        foreach($relations as $key => $relations){
            if( $name == $key ){
                
                $className = $this->modulPrefix.'Models\\'.ucwords($relations[1]);
                
                $name = new $className;
                $findBy = 'getBy'.$name->primaryKey;
                
                if( $relations[0] == 1 ){
                    
                    $name->limit(1);
                    return $name->$findBy( $this->$relations[2] );
                }
                elseif( $relations[0] == 2 ){
                    
                    $findBy = 'getBy'.$relations[2];
                    $pk = $this->primaryKey;
                    
                    $name->limit(1);
                    return $name->$findBy( $this->$pk );
                }
                elseif( $relations[0] == 3 ){
                    
                    $pk = $this->primaryKey;
                    $name->condition($relations[2], '=', $this->$pk, 'AND');
                    return $name;
                }
                elseif( $relations[0] == 4 ){
                    
                    $pk = $this->primaryKey;
                    
                    $name->select($relations[1].'.*');
                    $name->from( $relations[2][0] );
                    $name->condition($relations[2][0].'.'.$relations[2][2], '=', $this->$pk, 'AND');
                    $name->condition($relations[2][0].'.'.$relations[2][1], '=', $relations[1].'.'.$name->primaryKey, 'AND');
                    
                    return $name;
                }
            }
        }
    }
    
}