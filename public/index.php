<?php

require_once '../vendor/autoload.php';

use Caledonia\Application;

define('APPLICATION_PATH', realpath(__DIR__ . '/../app'));

$env = getenv('APPLICATION_ENV') ?: 'production';


// CONFIGURATION
// set configuration values. First set globals, then overwrite with environment config.
// Note: remember trailing slash on the end of the directory names

// move this lot into something like 

$config = Application::getConfig(array(
    APPLICATION_PATH . '/config/', // global configuration (should come first)
    APPLICATION_PATH . '/config/' . $env . '/', // environment configuration
));







// BOOTSTRAP
// Bootstrap file where we'll put everything to get up and running

$dbConfig = $config['database'];

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $dbConfig['driver'],
    'host'      => $dbConfig['host'],
    'database'  => $dbConfig['database'],
    'username'  => $dbConfig['username'],
    'password'  => $dbConfig['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();

// Setup the Eloquent ORM
$capsule->bootEloquent();



// RUN

Application::init($config, APPLICATION_PATH);
