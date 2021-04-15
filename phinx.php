<?php

$cleardb_conn_params = (function() {
    var_dump(getenv());
    $cleardb_url = current(array_filter(getenv(), function($key) {
        return preg_match('/^CLEARDB_[A-Z]+_URL$/', $key) === 1 ;
    }, ARRAY_FILTER_USE_KEY));
    $cleardb_conn_params = parse_url($cleardb_url);

    return array(
        "host" => !empty($cleardb_url) ? $cleardb_conn_params["host"] : '',
        "user" => !empty($cleardb_url) ? $cleardb_conn_params["user"] : '',
        "pass" => !empty($cleardb_url) ? $cleardb_conn_params["pass"] : '',
        "name" => !empty($cleardb_url) ? substr($cleardb_conn_params["path"], 1) : '',
    );
})();

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
            'host' => $cleardb_conn_params['host'],
            'name' => $cleardb_conn_params['name'],
            'user' => $cleardb_conn_params['user'],
            'pass' => $cleardb_conn_params['pass'],
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
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
