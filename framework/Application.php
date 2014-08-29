<?php

namespace framework;

/**
* Application
*    
* desc
*    
*/
class Application
{
    /**
    * __construct
    *    
    * desc
    *    
    */
    static public function init($config)
    {
        if(isset($_REQUEST['url'])) {
            
            // explode the url passed from the mod rewrite
            $q = explode('/', $_REQUEST['url']);
            
            $controller = 'app\Controller\\' . ucfirst(array_shift($q)) . 'Controller';
            $controller = new $controller;
            
            // set the controller, action and arguments
            $action = (count($q)) ? array_shift($q) : 'index';
            $arguments = (count($q)) ? $q : array();
        } else {
            
            $default = $config['routes']['default'];
            
            // call the default method
            $controller = 'app\Controller\\' . ucfirst($default['controller']) . 'Controller';
            $controller = new $controller;
            $action = $default['action'];
            $arguments = array();
        }

        call_user_func_array(array($controller, $action), $arguments);
    }
}
