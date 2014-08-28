<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/app'));

$config = include APPLICATION_PATH . '/config/application.config.php';

require 'vendor/autoload.php';

function dump($data) {
  framework\Debug\Debug::dump($data);
}


$dbAdapter = new framework\Db\DatabaseAdapter($config['database']);

$userTable = new app\models\UserTable($dbAdapter);

$result = $serTable->create('invalid argument type');
