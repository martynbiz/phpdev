<?php

return array(
    'database' => array(
        'dsn' => 'mysql:host=localhost;dbname=budgetz',
        'user' => 'root',
        'password' => 't1nth3p4rk',
    ),
    'routes' => array(
        'default' => array(
            'controller' => 'user',
            'action' => 'login'
        ),
    ),
);
