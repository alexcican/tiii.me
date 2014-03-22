<?php
/**
 * Panada MongoDB API.
 *
 * @package	Driver
 * @subpackage	Database
 * @author	Iskandar Soesman
 * @since	Version 0.2
 */
namespace Drivers\Database;
use Resources\Tools as Tools,
    Resources\RunException as RunException;

class Mongodb extends \Mongo
{    
    private
        $database,
        $config,
        $connection,
        $criteria = array(),
        $documents = array();

    protected
        $limit = null,
        $offset = null,
        $order = array();
            	
    public function __construct( $config, $connectionName )
    {    
        /**
        * Makesure Mongo extension is enabled
        */
       if( ! class_exists('Mongo') )
           throw new RunException('Mongo PECL extension that required by Mongodb Driver is not available.');
        
        $this->config = $config;
        $this->connection = $connectionName;
        
        /**
         * $this->config['options'] is the mongodb connection option. Eg: array('replicaSet' => true, 'connect' => false)
         */
	try {
	    parent::__construct($this->config['host'], $this->config['options']);
	}
	catch(\MongoConnectionException $e) {
	    RunException::outputError( 'Unable connect to database in <strong>'.$connectionName.'</strong> connection.' );
	}
    }
    
    /**
     * Define the db name
     *
     * @param string $database
     * @return object
     */
    public function database($database)
    {    
        $this->config['database'] = $database;
        return $this;
    }
    
    /**
     * Define the collection name
     *
     * @param string $database
     * @return object
     */
    public function collection($collection)
    {    
        $database = $this->config['database'];
        $db = $this->$database;
        return $db->$collection;
    }
    
    /**
     * Return the mongodb object after
     * the db selected
     *
     * @return object
     */
    public function mongoObj()
    {
	return $this->selectDB($this->config['database']);
    }
    
    /**
     * Wrap results from mongo output into object or array.
     *
     * @param array $cursor The array data given from Mongodb
     * @param string $output The output type: object | array
     * @return boolean | object | array
     */
    public function cursorResults($cursor, $output = 'object')
    {    
        if( ! $cursor )
            return false;
        
        $return = false;
        
        if( $output == 'array')
	    return iterator_to_array($cursor);
        
        foreach ($cursor as $value)
            $return[] = (object) Tools::arrayToObject($value);
            
        return $return;
        
    }
    
    /**
     * Convert string time into mongo date
     *
     * @param string $str
     */
    public function date($str = null)
    {    
        if( is_null($str) )
            return new \MongoDate();
        
        if( is_string($str) )
            return new \MongoDate(strtotime($str));
	
	if( is_int($str) )
	    return new \MongoDate($str);
    }
    
    /**
     * Convert a string unique identifier into MongoId object.
     *
     * @param string $_id Mongodb string id
     * @return object
     */
    public function _id($_id = null)
    {    
        return new \MongoId($_id);
    }
    
    /**
     * Method for select field(s) in a collection.
     *
     * @return object
     */
    public function select()
    {
	$documents = func_get_args();
	
        if( ! empty($documents) )
 	    $this->documents = $documents;
	
	if( is_array($documents[0]) )
	    $this->documents = $documents[0];

        return $this;
    }
    
    /**
     * Get the collection name.
     *
     * @return object
     */
    public function from($collectionName)
    {
	$this->collectionName = $collectionName;
	
	return $this;
    }
    
    /**
     * Build criteria condition.
     * Translate SQL like operator into mongo string operator.
     *
     * @param string | array Document field
     * @param string SQL operator
     * @param string Vlaue to compare
     * @param string Separator for more then one condition
     * @return object
     */
    public function where($document, $operator = null, $value = null, $separator = false)
    {
        if( is_array($document) )
            $this->criteria = $document;
        
        if($operator == '=')
            $this->criteria[$document] = $value;
	
	if($operator == '>')
            $this->criteria[$document]['$gt'] = $value;
	
	if($operator == '<')
            $this->criteria[$document]['$lt'] = $value;
	
	if($operator == '>=')
            $this->criteria[$document]['$gte'] = $value;
	
	if($operator == '<=')
            $this->criteria[$document]['$lte'] = $value;
        
	
        return $this;
    }
    
    /**
     * Find more then one document
     *
     * @return mix
     */
    public function getAll( $collection = false, $criteria = array(), $fields = array() )
    {
	if( $collection )
	    $this->collectionName = $collection;
	
	if( ! empty($criteria) )
	    $this->criteria = $criteria;
	
	if( ! empty($fields) )
	    $this->documents = $fields;
        
	$value = $this->collection($this->collectionName)->find( $this->criteria, $this->documents )->limit($this->limit)->skip($this->offset);
	
	if(count($this->order) > 0)
	    $value = $value->sort($this->order);
	
	$this->criteria = $this->documents = array();
	
	if( ! empty($value) )
	    return $this->cursorResults($value);
	
	return false;
    }
    
    /**
     * Find a document
     *
     * @return mix
     */
    public function getOne( $collection = false, $criteria = array(), $fields = array() )
    {    
	if( $collection )
	    $this->collectionName = $collection;
	
	if( ! empty($criteria) )
	    $this->criteria = $criteria;
	
	if( ! empty($fields) )
	    $this->documents = $fields;
	    
	$value = $this->collection($this->collectionName)->findOne( $this->criteria, $this->documents );
	$this->criteria = $this->documents = array();
	
	if( ! empty($value) )
	    return Tools::arrayToObject($value);
	
	return false;
    }
    
    /**
     * Insert new document
     *
     * @param string Collection name
     * @param array Data to insert
     * @return bool
     */
    public function insert($collection, $data = array())
    {    
        return $this->collection($collection)->insert($data); 
    }
    
    
    /**
     * Update document
     *
     * @param string Collection name
     * @param array Data to update
     * @param string SQL like criteria
     * @return bool
     */
    public function update($collection, $data, $criteria = null)
    {
	$this->where($criteria);
	$value = $this->collection($collection)->update( $this->criteria, array('$set' => $data) );
	$this->criteria = array();
	
	return $value;
    }
    
    /**
     * Delete a document
     *
     * @param string Collection name
     * param string SQL like criteria
     * @return bool
     */
    public function delete( $collection, $criteria = null )
    {    
	if( ! empty($criteria) )
	    $this->where($criteria);
	
	$value = $this->collection($collection)->remove( $this->criteria );
	$this->criteria = array();
	
	return $value;
    }   

    /**
     * Order By part when finding a document
     *
     * @param string $column, $order
     * @return object
     */
    public function orderBy( $column, $order = 'asc' )
    {
	$order = strtolower($order);
	
	if( $order=='desc' ) 
		$order = -1;
	else
		$order = 1;
		
	$this->order[$column] = $order;
	
	return $this;
    }    

    /**
     * Limit and Offset part when finding a document
     *
     * @param string $limit, $offset
     * @return object
     */
    public function limit( $limit, $offset = null )
    {
	$this->limit = $limit;
	$this->offset = $offset;
	
	return $this;
    }
    
}