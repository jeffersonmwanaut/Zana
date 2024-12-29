<?php
/**
 * This is the production mode configuration file.
 * It contains the configuration directives that give instructions to Zana in production mode.
 * Each directive is given a unique key:
 * 'key' => <value>
 * NOTE: This configuration is provided with some required keys which you should not modify without understanding what they do.
 */

/**
 * root: The top of the directory tree under which the project files are kept.
 */
$root = isset($root) ? $root : dirname(__DIR__, 3);

/**
 * domain: The server on which the project is hosted.
 */
$domain = $_SERVER['SERVER_NAME'];

/**
 * urlRoot: The project directory. Replace value project-dir with your own.
 */
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$urlRoot = str_replace('/public/app.php', '', $protocol . '://'. $domain . $_SERVER['PHP_SELF']);

/**
 * Configuration entries:
 * NOTE: Do not remove native keys which required to Zana. Nevertheless, you can add your own keys and their values.
 */
return [
    # database configuration
    'db' => [
        # MySql database
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
        # PostgreSql
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
    ]
];