<?php

use Connehito\CakephpMasterReplica\Database\Connection\MasterReplicaConnection;

return [
    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'defaultLocale' => 'ja-JP',
        'defaultTimezone' => 'Asia/Tokyo',
        'fullBaseUrl' => 'https://dev.lancers.jp',
    ],

    'AWS' => [
        'credentials' => [
            'key' => '',
            'secret'  => '',
        ],
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
            'url' => null,
        ],
    ],

    'Error' => [
        'errorLevel' => E_ALL,
        'exceptionRenderer' => ExceptionRenderer::class,
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],

    'Datasources' => [
        'default' => [
            'className' => MasterReplicaConnection::class,
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'database' => 'lancers',
            'encoding' => 'utf8mb4',
            'timezone' => 'Asia/Tokyo',
            'flags' => [],
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            'log' => false,
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
            'url' => null,
        ],

        'test' => [
            'className' => MasterReplicaConnection::class,
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'database' => 'lancers_test',
            'encoding' => 'utf8mb4',
            'timezone' => 'Asia/Tokyo',
            'flags' => [],
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            'log' => false,
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
            'url' => null,
        ],
    ],
];
