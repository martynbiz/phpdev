<?php

namespace framework\Model;

use framework\Db\IDatabaseAdapter;

class RowGateway {

    protected $_tableName; // name of the table to map to
    protected $_dbAdapter; // database adapter

    public function __construct($values=null, IDatabaseAdapter $dbAdapter=null) {

        $this->_tableName = strtolower(get_called_class());
        $this->_dbAdapter = $dbAdapter;

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function save() {
        // throw exception where dbadapter has not been set
    }

    public function delete() {
        // throw exception where dbadapter has not been set
    }

    public function toArray() {

        return get_object_vars($this);
    }
}
