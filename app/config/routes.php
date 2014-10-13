<?php

return array(
    'routes' => array(
        'match' => array(
            'articles' => array(
                'controller' => 'posts',
                'action' => 'index',
            ),
            'articles/top5' => array(
                'controller' => 'posts',
                'action' => 'recent',
                'params' => array(5)
            ),
        ),
        'resources' => array(
            'posts',
            'users',
        ),
        'default' => array(
            'controller' => 'posts',
            'action' => 'index',
        ),
    ),
);
