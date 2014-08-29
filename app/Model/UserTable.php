<?php

namespace app\Model;

use framework\Model\TableGateway;

class UserTable extends TableGateway {

    /**
    * create
    *
    * Custom method for fetching all admin users
    *
    * @return array Array of row object instances
    */
    public function create(array $values, $options=array())
    {
        // set datetime columns
        if(! isset($values['date_created']) or empty($values['date_created'])) {
            $values['date_created'] = date("Y-m-d H:i:s");
        }
        if(! isset($values['date_updated']) or empty($values['date_updated'])) {
            $values['date_updated'] = date("Y-m-d H:i:s");
        }
        
        return parent::create($values);
    }
    
    /**
    * update
    *
    * Custom method for updating rows
    * 
    * @params array $values Array of column/values to update
    * @params array $where Where array criteria
    *
    * @return boolean Array of row object instances
    */
    public function update($values, $where, $options=array())
    {
        // set datetime column
        if(! isset($values['date_updated']) or empty($values['date_updated'])) {
            $values['date_updated'] = date("Y-m-d H:i:s");
        }
        
        return parent::update($values, $where);
    }

}
