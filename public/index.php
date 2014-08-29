<?php

require_once '../vendor/autoload.php';

define('APPLICATION_PATH', realpath(__DIR__ . '/../app'));

$config = require APPLICATION_PATH . '/config/application.config.php';

framework\Application::init($config);
