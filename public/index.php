<?php

require '../vendor/autoload.php';

define('APPLICATION_PATH', realpath(__DIR__ . '/../app'));

if(isset($_REQUEST['url'])) {
    
    // explode the url passed from the mod rewrite
    $q = explode('/', $_REQUEST['url']);
    
    // set the controller, action and arguments
    $url = array(
        'controller' => array_shift($q),
        'action' => (count($q)) ? array_shift($q) : 'index',
        'arguments' => (count($q)) ? $q : array(),
    );
    
    $controller = new \martynbiz\framework\Controller;
    
    var_export($controller);
    //var_export($url);
}
