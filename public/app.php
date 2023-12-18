<?php
require '../vendor/autoload.php';
// Initialize config
\Zana\Config\Config::getInstance();
// Define constants from config parameters
define('CONFIG', \Zana\Config\Config::get());
define('ROOT', \Zana\Config\Config::get('path')['root']);
define('URL_ROOT', \Zana\Config\Config::get('path')['url_root']);
define('BOOTSTRAP', \Zana\Config\Config::get('path')['bootstrap']);
define('FONTAWESOME', \Zana\Config\Config::get('path')['fontawesome']);
define('FAVICON', \Zana\Config\Config::get('path')['favicon']);
define('JQUERY', \Zana\Config\Config::get('path')['jquery']);
define('IMG', \Zana\Config\Config::get('path')['img_root']);
define('CSS', \Zana\Config\Config::get('path')['css_root']);
define('JS', \Zana\Config\Config::get('path')['js_root']);
define('GOOGLE_FONTS', \Zana\Config\Config::get('path')['google_fonts']);
// Initialize router
\Zana\Router\Router::getInstance();
// Initialize app
\Zana\Application::getInstance();
\Zana\Application::run();