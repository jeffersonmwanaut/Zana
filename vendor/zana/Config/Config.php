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
        self::$mode = file_exists($userConfigDir . '/mode.env') ? file_get_contents($userConfigDir . DIRECTORY_SEPARATOR . 'mode.env') : file_get_contents($vendorConfigDir . DIRECTORY_SEPARATOR . 'mode.env');
        
        // Get config directives
        $vendorCommonConfig = require_once($vendorConfigDir . '/com.php');
        $vendorModeConfig   = require_once($vendorConfigDir . '/' . self::$mode . '.php');
        
        $vendorModules = json_decode($vendorConfigDir . '/modules.json', true)['modules'];
        
        $userModeConfig       = $userCommonConfig = $userModules = [];
        $userCommonConfigFile = $userConfigDir . '/com.json';
        $userModulesFile      = $userConfigDir . '/modules.json';
        $userConfigFile       = $userConfigDir . '/' . self::$mode . '.json';
        
        if (file_exists($userCommonConfigFile)) $userCommonConfig = json_decode(file_get_contents($userCommonConfigFile), true);
        if (file_exists($userConfigFile)) $userModeConfig         = json_decode(file_get_contents($userConfigFile), true);
        if (file_exists($userModulesFile)) $userModules           = json_decode($userModulesFile, true)['modules'];
        
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
        
        return $value;
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
