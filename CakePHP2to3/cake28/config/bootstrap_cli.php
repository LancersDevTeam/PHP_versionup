<?php

if (!defined('CONFIG_CAKEPHP3')) {
    define('CONFIG_CAKEPHP3', __DIR__ . '/../../config/');
}

if (!env('DB_MASTER_HOST') && file_exists(CONFIG_CAKEPHP3 . '.env')) {
    $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG_CAKEPHP3 . '.env']);
    $dotenv->parse()
        ->putenv()
        ->toEnv()
        ->toServer();
}
