<?php

namespace app\Controller;

use app\Model\UserTable;

/**
* UserController
*    
* desc
*    
*/
class UserController extends ApplicationController
{
    /**
    * login
    *    
    * desc
    *    
    */
    public function login()
    {
        $this->view('user/login', array(
            'id' => 12,
        ));
    }
}
