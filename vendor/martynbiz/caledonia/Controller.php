<?php

namespace Caledonia;

/**
* Controller
*    
* desc
*    
*/
class Controller
{
    public $appBase;
    
    public $params;
    
    /**
    * __construct
    *    
    * desc
    *    
    */
    public function __construct($appBase)
    {
        $this->appBase = $appBase;
    }
    
    /**
    * view
    *    
    * Renders the view
    *    
    */
    public function view($view, $params=array())
    {
        $viewPath = $this->appBase . '/views/' . $view . '.php';
        
        $this->params = $params;
        
        if(! file_exists($viewPath))
            throw new \Exception('View script missing (' . $view . ')');
        
        require_once $viewPath;
    }
}
