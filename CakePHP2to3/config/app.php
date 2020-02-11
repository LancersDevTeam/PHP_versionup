<?php

return [
    'App' => [
        'namespace' => 'App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => env('APP_DEFAULT_LOCALE', 'ja-JP'),
        'defaultTimezone' => env('APP_DEFAULT_TIMEZONE', 'Asia/Tokyo'),
    ],

    'Cache' => [
        '_cake_model_' => [
            'className' => 'Cake\Cache\Engine\ApcuEngine',
            'prefix' => 'lancers_cake_model_',
            'path' => CACHE . 'models/',
            'duration' => '+1 years',
        ],
        '_cake_core_' => [
            'className' => 'Cake\Cache\Engine\ApcuEngine',
            'prefix' => 'lancers_cake_core_',
            'path' => CACHE . 'persistent/',
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKECORE_URL', null),
        ],
    ],

    'Datasources' => [
        'default' => [
            'className'        => 'Cake\Database\Connection',
            'driver'           => 'Cake\Database\Driver\Mysql',
            'persistent'       => false,
            'host'             => env('DB_MASTER_HOST', ''),
            'port'             => env('DB_MASTER_PORT', ''),
            'username'         => env('DB_MASTER_USERNAME', ''),
            'password'         => env('DB_MASTER_PASSWORD', ''),
            'database'         => 'lancers',
            'encoding'         => 'utf8mb4',
            'timezone'         => 'UTC',
            'flags'            => [],
            'cacheMetadata'    => true,
            'quoteIdentifiers' => false,
            'log'              => false,
        ],

        'slave' => [
            'className'        => 'Cake\Database\Connection',
            'driver'           => 'Cake\Database\Driver\Mysql',
            'persistent'       => false,
            'host'             => env('DB_SLAVE_HOST', ''),
            'port'             => env('DB_SLAVE_PORT', ''),
            'username'         => env('DB_SLAVE_USERNAME', ''),
            'password'         => env('DB_SLAVE_PASSWORD', ''),
            'database'         => 'lancers',
            'encoding'         => 'utf8mb4',
            'timezone'         => 'UTC',
            'flags'            => [],
            'cacheMetadata'    => true,
            'quoteIdentifiers' => false,
            'log'              => false,
        ],

        'test' => [
            'className'        => 'Cake\Database\Connection',
            'driver'           => 'Cake\Database\Driver\Mysql',
            'persistent'       => false,
            'host'             => env('DB_MASTER_HOST', ''),
            'port'             => env('DB_MASTER_PORT', ''),
            'username'         => env('DB_MASTER_USERNAME', ''),
            'password'         => env('DB_MASTER_PASSWORD', ''),
            'database'         => 'lancers_test',
            'encoding'         => 'utf8mb4',
            'timezone'         => 'UTC',
            'flags'            => [],
            'cacheMetadata'    => true,
            'quoteIdentifiers' => false,
            'log'              => false,
        ],
    ],
];
