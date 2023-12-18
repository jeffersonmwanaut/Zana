<?php namespace Zana\Config;

/**
 * Class Config
 * @package Kesi\Config
 */
class Config
{
    /**
     * @var string
     */
    private static $mode;
    /**
     * Link up to the configuration file.
     * @var array|mixed
     */
    private static $settings = [];
    /**
     * Unique instance of Config.
     * @var Config
     */
    private static $instance = null;

    private function __construct()
    {
        // Get dirs
        $vendorConfigDir = __DIR__;
        $userConfigDir = dirname($vendorConfigDir, 3) . '/config';

        // Get mode
        self::$mode = file_exists($userConfigDir . '/mode.txt') ? file_get_contents($userConfigDir . DIRECTORY_SEPARATOR . 'mode.txt') : file_get_contents($vendorConfigDir . DIRECTORY_SEPARATOR . 'mode.txt');
        
        // Get config directives
        $vendorCommunConfig = require_once($vendorConfigDir . '/com.php');
        $vendorModeConfig = require_once($vendorConfigDir . '/' . self::$mode . '.php');
        
        $vendorModules = require_once($vendorConfigDir . '/modules.php');
        
        $userModeConfig = $userCommunConfig = $userModules = [];
        $userCommunConfigFile = $userConfigDir . '/com.php';
        $userModulesFile = $userConfigDir . '/modules.php';
        $userConfigFile = $userConfigDir . '/' . self::$mode . '.php';
        
        if (file_exists($userCommunConfigFile)) $userCommunConfig = require_once($userConfigDir . '/com.php');
        if (file_exists($userConfigFile)) $userModeConfig = require_once($userConfigDir . '/' . self::$mode . '.php');
        if(file_exists($userModulesFile)) $userModules = require_once($userConfigDir . '/modules.php');
        
        $vendorConfig = array_merge_recursive($vendorModeConfig, $vendorCommunConfig);
        $userConfig = array_merge_recursive($userModeConfig, $userCommunConfig);
        $modules = array_merge_recursive($vendorModules, $userModules);
        self::$settings = array_replace_recursive($vendorConfig, $userConfig, $modules);
    }

    /**
     * Retrieve a configuration value using its key.
     * @param string $key
     * @return mixed|null
     */
    public static function get($key = null)
    {
        if(is_null($key)) return self::$settings;
        if (!isset(self::$settings[$key])) {
            return null;
        }
        return self::$settings[$key];
    }

    /**
     * Get configuration mode
     * @return bool|string
     */
    public static function mode()
    {
        return self::$mode;
    }

    /**
     * Retrieve the unique instance of Config.
     * @return Config
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Config) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * Return configuration
     * @return array|mixed
     */
    public static function getConfig()
    {
        return self::mode() ? self::$settings : [];
    }
}