<?php

namespace framework\Db;

class DatabaseAdapter implements IDatabaseAdapter {
    
    protected $_conn;
    
    public function __construct(array $dbConfig) {
    
    try {
      $this->_conn = new \PDO($dbConfig['dsn'], $dbConfig['user'], $dbConfig['password']);
    } catch (PDOException $e) {
      echo 'Cannot connect: ',  $e->getMessage(), "\n";
    }
    }

    /**
    * select
    *
    * Select from a table.
    *
    * @param string $tableName Name of the table.
    * @param mixed $where Where conditions to apply.
    * @param array $options Additional options such as limit, start etc.
    *
    * @return array Array of row object instances.
    */
    public function select($tableName, $where=null, $options=array()) {

        // these are the values for each ? in our query template
        $templateValues = array();

        // build the query
        $sql = 'SELECT * FROM ' . $tableName;
        $this->_setWhere($sql, $templateValues, $where);
        $this->_setOptions($sql, $templateValues, $options);
        
        // prepare the statement
        $stmt = $this->_conn->prepare($sql);

        // execute
        $stmt->execute($templateValues);

        return $stmt->fetchAll();
	}
	
	/**
  * insert
  *
  * Inserts a single row or multiple rows.
  *
  * @param string $tableName Name of the table to update.
  * @param array $values Values of rows to be created. Can be a multiple dimensional array for multiple inserts.
  *
  * @return boolean It will return false if at least one row was not inserted. Otherwise it will return true.
  */
	public function insert($tableName, array $values, $options=array()) {
		
		// if a single insert, let's prepare the array as though it were multiple inserts (array of arrays) to keep things consistent further on
    if(! isset($values[0])) {
      $values = array($values);
    }
    
    // build the query
    $sql = 'INSERT INTO '.$tableName.' (' . 
    	implode(', ', array_keys($values[0])) . 
    ') VALUES (' . 
	    implode(', ', array_fill(0, count($values[0]), '?')) . 
    ')';
    
    // prepare the statement
    $stmt = $this->_conn->prepare($sql);
    
    // loop through each item in $values and execute the prepared statement
    $result = true;
    foreach($values as $value) {
      if (! $stmt->execute(array_values($value))) {
      	$result = false;
      }
    }
    
    return $result;
	}
	
	/**
  * update
  *
  * Updates rows in the table.
  *
  * @param string $tableName Name of the table.
  * @param array $values New values for rows to be updated.
  * @param mixed $where Where conditions to apply.
  * @param array $options Additional options such as limit, start etc.
  *
  * @return boolean Result of execute.
  */
	public function update($tableName, array $values, $where=null, $options=array()) {
		
		// these are the values for each ? in our query template
		$templateValues = array();
		
		// generate name values string for the UPDATE
		
		$nameValuePairs = array();
		
		foreach($values as $key => $value) {
			array_push($nameValuePairs, $key . ' = ?');
			array_push($templateValues, $value);
		}
		
		// build the query
		$sql = 'UPDATE ' . $tableName . ' SET ' . implode(', ', $nameValuePairs);
		$this->_setWhere($sql, $templateValues, $where);
		$this->_setOptions($sql, $templateValues, $options);
		
    // prepare the statement
    $stmt = $this->_conn->prepare($sql);
    
    // execute
    return $stmt->execute($templateValues);
	}
	
	/**
  * delete
  *
  * Delete rows in the table.
  *
  * @param string $tableName Name of the table.
  * @param mixed $where Where conditions to apply.
  * @param array $options Additional options such as limit, start etc.
  *
  * @return boolean Result of execute.
  */
	public function delete($tableName, $where=null, $options=array()) {
		
		// these are the values for each ? in our query template
		$templateValues = array();
		
		// build the query
		$sql = 'DELETE FROM ' . $tableName;
		$this->_setWhere($sql, $templateValues, $where);
		$this->_setOptions($sql, $templateValues, $options);
		
    // prepare the statement
    $stmt = $this->_conn->prepare($sql);
    
    // execute
    return $stmt->execute($templateValues);
	}
	
	/**
  * _setWhere
  *
  * Sets the WHERE part of a query.
  *
  * @param string $sql The SQL query to set.
  * @param array $templateValues Values which are passed to execute method to set.
  * @param mixed $where Where conditions to apply to query.
  *
  * @return boolean It will return false if at least one row was not inserted. Otherwise it will return true.
  */
	protected function _setWhere(&$sql, array &$templateValues, $where) {
		
		if (is_array($where)) {
			$sql.= ' WHERE ' . $where[0];
			if (isset($where[1])) $templateValues = array_merge($templateValues, $where[1]);
		} elseif(! is_null($where)) {
			$sql.= ' WHERE ' . $where;
		}
	}
	
	/**
  * _setOptions
  *
  * Sets the options (ORDER BY, LIMIT etc).
  *
  * @param string $sql The SQL query to set.
  * @param array $templateValues Values which are passed to execute method to set.
  * @param array $options Additional options such as limit, start etc to set.
  *
  * @return boolean It will return false if at least one row was not inserted. Otherwise it will return true.
  */
	protected function _setOptions(&$sql, &$templateValues, array $options) {
		
		// set order by
		
		if (isset($options['orderBy']) and is_array($options['orderBy'])) {
			
			// removing spaces to prevent SQL injections
			$options['orderBy'][0] = str_replace(' ', '', $options['orderBy'][0]);
			
			// set column
			$sql.= ' ORDER BY ' . $options['orderBy'][0];
			
			// set directions
			if (isset($options['orderBy'][1])) {
				$options['orderBy'][1] = str_replace(' ', '', $options['orderBy'][1]);
				$sql.= ' ' . $options['orderBy'][1];
			}
		}
		
		// set limit
		
		if (isset($options['limitMax']) and isset($options['limitStart'])) {
			
			// ensure that these are numeric
			$options['limitMax'] = (integer) $options['limitMax'];
			$options['limitStart'] = (integer) $options['limitStart'];
			
			$sql.= ' LIMIT ' . $options['limitStart'] . ', ' . $options['limitMax'];
			
		} elseif (isset($options['limitMax'])) {
			
			// ensure that these are numeric
			$options['limitMax'] = (integer) $options['limitMax'];
			
			$sql.= ' LIMIT ' . $options['limitMax'];
			
		}
	}
	
}
