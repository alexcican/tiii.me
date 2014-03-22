<?php
/**
 * Interface for Database Drivers
 *
 * @author Iskandar Soesman <k4ndar@yahoo.com>
 * @link http://panadaframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @since version 1.0.0
 * @package Core System
 */
namespace Resources\Interfaces;

interface Database
{    
    public function select();
    public function distinct();
    public function from();
    public function join( $table, $type = null );
    public function on( $column, $operator, $value, $separator = false );
    public function where( $column, $operator, $value, $separator = false );
    public function groupBy();
    public function having( $column, $operator, $value, $separator = false );
    public function orderBy( $column, $order = null );
    public function limit( $limit, $offset = null );
    public function command();
    public function begin();
    public function commit();
    public function rollback();
    public function escape( $string );
    public function query( $sql );
    public function getAll( $table = false, $where = array(), $fields = array() );
    public function getOne( $table = false, $where = array(), $fields = array() );
    public function getVar( $query = null );
    public function results( $query, $type = 'object' );
    public function row( $query, $type = 'object' );
    public function insert( $table, $data = array() );
    public function insertId();
    public function update( $table, $dat, $where = null );
    public function delete( $table, $where = null );
    public function version();
    public function close();
}
