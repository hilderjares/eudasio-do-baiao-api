<?php

define('APP_ROOT', __DIR__);

return [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,

        'doctrine' => [

            'dev_mode' => true,

            'cache_dir' => '../storage/var/doctrine',

            'metadata_dirs' => [
                'src/Entity',
            ],

            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => '0.0.0.0',
                'port' => 3306,
                'dbname' => 'reuso_trabalho',
                'user' => 'root',
                'password' => 'root',
                'charset' => 'utf8'
            ],
        ],
    ],
];