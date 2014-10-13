<?php

namespace app\Controller;

use app\Model\User;

/**
* UserController
*    
* desc
*    
*/
class UsersController extends ApplicationController
{
    /**
    * login
    *    
    * desc
    *    
    */
    public function loginAction()
    {
        $user = User::find(1)->toArray();
        
        $this->view('users/login', array(
            'user' => $user,
        ));
    }
}
