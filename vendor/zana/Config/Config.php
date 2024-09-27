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
        // Get config directories
        $vendorConfigDir = __DIR__;
        $userConfigDir = dirname(__DIR__, 3) . '/config';

        // Get config mode
        self::$mode = file_exists($userConfigDir . '/mode.env') ? file_get_contents($userConfigDir . DIRECTORY_SEPARATOR . 'mode.env') : file_get_contents($vendorConfigDir . DIRECTORY_SEPARATOR . 'mode.env');
        
        // Get vendor config 
        $vendorCommonConfig = require_once($vendorConfigDir . '/com.php');
        $vendorModeConfig   = require_once($vendorConfigDir . '/' . self::$mode . '.php');
        
        $vendorModules = json_decode(file_get_contents($vendorConfigDir . '/modules.json'), true);
        
        // Get user config
        $userModeConfig       = $userCommonConfig = $userModules = [];
        $userCommonConfigFile = $userConfigDir . '/com.json';
        $userModulesFile      = $userConfigDir . '/modules.json';
        $userConfigFile       = $userConfigDir . '/' . self::$mode . '.json';
        
        if (file_exists($userCommonConfigFile)) $userCommonConfig = json_decode(file_get_contents($userCommonConfigFile), true);
        if (file_exists($userConfigFile)) $userModeConfig         = json_decode(file_get_contents($userConfigFile), true);
        if (file_exists($userModulesFile)) $userModules           = json_decode(file_get_contents($userModulesFile), true);
        
        $vendorConfig   = array_merge_recursive($vendorModeConfig, $vendorCommonConfig);
        $userConfig     = array_merge_recursive($userModeConfig, $userCommonConfig);
        $modules        = array_merge_recursive($vendorModules, $userModules);
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

        $keyParts = explode('.', $key);
        $keyLenght = count($keyParts);
        $value = self::$settings;
        
        $index = 0;
        while($index < $keyLenght) {
            if(!isset($value[$keyParts[$index]])) {
                return null;
            }
            $value = $value[$keyParts[$index]];
            $index++;
        }
        
        return self::resolvePath($value);
    }

    private static function resolvePath($path)
    {
        // Check if the value is a path and needs to be resolved
        if (is_string($path) && strpos($path, './') === 0) {
            $path = self::resolveRelativePath($path);
        } elseif (is_string($path) && strpos($path, '//') === 0) {
            $path = self::resolveUrlPath($path);
        }

        return $path;
    }

    private static function resolveRelativePath($path)
    {
        return realpath(str_replace('./', self::$settings['path']['root'] . '/', $path));
    }

    private static function resolveUrlPath($path)
    {
        return str_replace('//', self::$settings['path']['url_root'] . '/', $path);
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
