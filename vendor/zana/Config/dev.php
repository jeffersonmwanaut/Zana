<?php
/**
 * This is the development mode configuration file.
 * It contains the configuration directives that give instructions to Mbaar for development environment.
 * 
 * Each directive is given a unique key and value.
 * 'key' => <value>
 * 
 * NOTE: This configuration is provided with some required keys that should not be edited without understanding what they do?
 */

/**
 * Configuration entries:
 * NOTE: Do not remove native keys which required to Zana. Nevertheless, you can add your own keys and their values.
 */
return [
    # database configuration
    'db' => [
        # MySql connection params
        'mysql' => [
            'host' => 'localhost',
            'port' => 3306,
            'name' => 'test',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ],
        # PostgreSql connection params
        'pgsql' => [
            'host' => 'localhost',
            'port' => 5432,
            'name' => 'test',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ],
        # SqLite connection params
        'sqlite' => $root . DIRECTORY_SEPARATOR . 'sqlite.db'
    ],
    'mail' => [
        'contact' => 'contact@' . $domain,
        'noreply' => 'noreply@' . $domain
    ],
    'path' => [
        'root' => $root,
        'url_root' => $urlRoot
    ]
];