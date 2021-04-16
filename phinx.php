<?php

include 'api/app/dbconfig.php';

$db_conn_params = load_db_config();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => getenv('ON_HEROKU') === "1" ? 'staging' : 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'staging' => [
            'adapter' => 'mysql',
            'host' => $conn_params['host'],
            'name' => $conn_params['name'],
            'user' => $conn_params['user'],
            'pass' => $conn_params['pass'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'mysql',
            'name' => 'cs450',
            'user' => 'cs450db_user',
            'pass' => 'tomtit.TAD.inward',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'mysql_for_tests',
            'name' => 'cs450',
            'user' => 'cs450db_user',
            'pass' => 'tomtit.TAD.inward',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
