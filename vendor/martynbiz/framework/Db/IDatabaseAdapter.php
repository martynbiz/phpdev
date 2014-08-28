<?php

namespace framework\Db;

interface IDatabaseAdapter {
	
	public function __construct(array $dbConfig);
	public function select($tableName, $where=null, $options=array());
	public function insert($tableName, array $values);
	public function update($tableName, array $values, $where=null, $options=array());
	public function delete($tableName, $where=null, $options=array());
}
