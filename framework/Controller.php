<?php

namespace framework ;

/**
* Controller
*    
* desc
*    
*/
class Controller
{
    protected $appBase;
    
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
        require_once $this->appBase . '/View/' . $view . '.php';
    }
}
