<?php

namespace Caledonia;

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
        $query = isset($_REQUEST['url']) ? $_REQUEST['url'] : null;
        
        $route = self::getRouteFromUrlQuery($query, $config['routes']);
        
        try {
            $controller = 'app\Controller\\' . $route['controller'] . 'Controller';
            $controller = new $controller($appBase);
            call_user_func_array(array($controller, $route['action']), $route['params']);
        } catch (\Exception $e) {
            die('Route not found');
        }
    }
    
    /**
    * getControllerName
    *    
    * Get the controller from the url string
    *    
    */
    static public function getRouteFromUrlQuery($query, $routes)
    {
        $route = array(
            'controller' => null,
            'action' => null,
            'params' => null,
        );
        
        if($query) {
            
            // explode the url passed from the mod rewrite
            $q = explode('/', $_REQUEST['url']);
            
            // loop through each route condition and process (skip default). return any matches.
            
            
            // lastly, attempt to just set an action, controller and params from the url
            $route['controller'] = ucfirst(array_shift($q));
            $route['action'] = (count($q)) ? array_shift($q) . 'Action' : 'indexAction';
            $route['params'] = (count($q)) ? $q : array();
            return $route;
            
        } else {
            
            $defaultRoute = isset($routes['default']) ? $routes['default'] : null;
            
            $route['controller'] = ucfirst($defaultRoute['controller']);
            $route['action'] = (isset($defaultRoute['action'])) ? $defaultRoute['action'] . 'Action' : 'indexAction';
            $route['params'] = (isset($defaultRoute['params'])) ? $defaultRoute['params'] : array();
            return $route;
        }
    }
    
    /**
    * getConfig
    *    
    * Get the config array from the files in app/config
    *    
    */
    public static function getConfig($configDirs)
    {
        $config = array();

        foreach($configDirs as $dir) {
            if (is_dir($dir)) {
                foreach (new \DirectoryIterator($dir) as $fileInfo) {
                    if($fileInfo->isDot() or $fileInfo->isDir()) continue;
                    $config = array_merge($config, require($dir . $fileInfo->getFilename()));
                }
            }
        }
        
        return $config;
    }
}
