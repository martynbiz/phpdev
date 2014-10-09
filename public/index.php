<?php

require_once '../vendor/autoload.php';

define('APPLICATION_PATH', realpath(__DIR__ . '/../app'));

$env = getenv('APPLICATION_ENV') ?: 'production';


// CONFIGURATION
// set configuration values. First set globals, then overwrite with environment config.
// Note: remember trailing slash on the end of the directory names

$config = array();
$configDirs = array(
    APPLICATION_PATH . '/config/', // global configuration (should come first)
    APPLICATION_PATH . '/config/' . $env . '/', // environment configuration
);

foreach($configDirs as $dir) {
    if (is_dir($dir)) {
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot() or $fileInfo->isDir()) continue;
            $config = array_merge($config, require($dir . $fileInfo->getFilename()));
        }
    }
}



// BOOTSTRAP
// Bootstrap file where we'll put everything to get up and running

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'phpdev_development',
    'username'  => 'root',
    'password'  => 't1nth3p4rk',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();

// Setup the Eloquent ORM
$capsule->bootEloquent();



// RUN

Caledonia\Application::init($config, APPLICATION_PATH);
