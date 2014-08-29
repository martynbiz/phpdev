<?php

return array(
    'database' => require 'database.config.php',
    'routes' => array(
        'default' => array(
            'controller' => 'user',
            'action' => 'login'
        ),
    ),
);
