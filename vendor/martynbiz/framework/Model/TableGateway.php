<?php

namespace framework\Model;

use framework\Db\IDatabaseAdapter;
use framework\Exception\MissingDependencyException;

abstract class TableGateway {

    protected $_tableName; // name of the table to map to
    protected $_rowObject; // row object
    protected $_dbAdapter; // database adapter

    public function __construct() {

        // extract the name from class namespace
        $tableName = get_called_class();
        if(is_numeric(strpos($tableName, '\\'))) {
            $tableName = explode('\\', $tableName); // has namespacing divs
        }
        $tableName = end($tableName);
        $tableName = str_replace('Table', '', $tableName); // strip 'Table'

        // set properties
        $this->_rowObject = $tableName;
        $this->_tableName = strtolower($tableName);
    }
    
    /**
    * getDatabaseAdapter
    *    
    * Gets the database adapter of the instance
    *    
    */
    public function getDatabaseAdapter()
    {
        return $this->_dbAdapter;
    }
    
    /**
    * setDatabaseAdapter
    *    
    * Sets the database adapter for the instance
    *    
    */
    public function setDatabaseAdapter(IDatabaseAdapter $dbAdapter)
    {
        return $this->_dbAdapter = $dbAdapter;
    }

    /**
    * find
    *
    * Select a single row from the table by it's ID, and return a row object instance of it
    *
    * @param integer $id ID of row
    *
    * @return array Associative array of row | false where not found
    */
    public function find($id) {
        
        if (! is_numeric($id)) {
            throw new \InvalidArgumentException('ID must be numeric');
        }
        
        $result = $this->getDatabaseAdapter()->select($this->_tableName, array('id = ?', array($id)));

        // select row by id column
        if($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
    * fetch
    *
    * Fetch rows from the table by criteria
    *
    * @param array $where Where string e.g. 'user_id = ?, active = ?' and values for the where string
    * @param array $options Options
    *
    * @return array Array of row object instances
    */
    public function fetch($where=null, $options=array()) {

        // will return this array with row instances
        $result = array();

        // select rows using the db adapter and the where attributes passed
        return $this->getDatabaseAdapter()->select($this->_tableName, $where, $options);
    }

    /**
    * fetchAll
    *
    * Fetch all rows from the table
    *
    * @param array $where Where string e.g. 'user_id = ?, active = ?' and values for the where string
    * @param array $options Options
    *
    * @return array Array of row object instances
    */
    public function fetchAll($where=null, $options=array()) {

        // will return this array with row instances
        $result = array();

        // select rows using the db adapter and the where attributes passed
        return $this->getDatabaseAdapter()->select($this->_tableName);
    }

    /**
    * insert
    *
    * Insert rows into the table
    *
    * @param array $values Named values to insert into the table. Can be a multi-dimensional array supporting multiple inserts.
    *
    * @return boolean True or false whether the query was successful
    */
    public function create(array $values, $options=array()) {
        
        if (! $dbAdapter = $this->getDatabaseAdapter()) {
            throw new \RuntimeException('Database adapter not set');
        }
        
        return $dbAdapter->insert($this->_tableName, $values, $options);
    }

    /**
    * update
    *
    * Update rows in the table
    *
    * @param array $values Named values to insert into the table. Can be a multi-dimensional array supporting multiple inserts.
    * @param string $where Where string e.g. 'user_id = ?, active = ?'
    * @param array $where Values for the where string
    * @param integer $max Maximum number of rows to update 
    * @param integer $start Where to start to update maximum number of rows 
    *
    * @return boolean True or false whether the query was successful
    */
    public function update($values, $where, $options=array()) {

        return $this->getDatabaseAdapter()->update($this->_tableName, $values, $where, $options);
    }

    /**
    * delete
    *
    * Delete rows in the table
    *
    * @param string $where Where string e.g. 'user_id = ?, active = ?'
    * @param array $where Values for the where string
    * @param integer $max Maximum number of rows to delete 
    * @param integer $start Where to start to delete maximum number of rows 
    *
    * @return boolean True or false whether the query was successful
    */
    public function delete($where, $options=array()) {

        return $this->getDatabaseAdapter()->delete($this->_tableName, $where, $options);
    }
}
