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
    static public function init($config, $appBase)
    {
        if(isset($_REQUEST['url'])) {
            
            // explode the url passed from the mod rewrite
            $q = explode('/', $_REQUEST['url']);
            
            $controller = 'app\Controller\\' . ucfirst(array_shift($q)) . 'Controller';
            $controller = new $controller($appBase);
            
            // set the controller, action and arguments
            $action = (count($q)) ? array_shift($q) : 'index';
            $params = (count($q)) ? $q : array();
        } else {
            
            $default = $config['routes']['default'];
            
            // call the default method
            $controller = 'app\Controller\\' . ucfirst($default['controller']) . 'Controller';
            $controller = new $controller($appBase);
            $action = $default['action'];
            $params = array();
        }

        call_user_func_array(array($controller, $action), $params);
    }
}
