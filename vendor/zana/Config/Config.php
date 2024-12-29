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
    private static string $mode;
    /**
     * Link up to the configuration file.
     * @var array
     */
    private static array $settings = [];
    /**
     * Unique instance of Config.
     * @var Config|null
     */
    private static ?Config $instance = null;

    private function __construct()
    {
        // Get config directories
        $vendorConfigDir = __DIR__;
        $userConfigDir = dirname(__DIR__, 3) . '/config';

        // Get config mode
        self::$mode = file_exists($userConfigDir . '/mode.env') ? file_get_contents($userConfigDir . DIRECTORY_SEPARATOR . 'mode.env') : file_get_contents($vendorConfigDir . DIRECTORY_SEPARATOR . 'mode.env');
        
        // Load vendor config
        $vendorCommonConfig = $this->loadConfigFile($vendorConfigDir . '/com.php');
        $vendorModeConfig = $this->loadConfigFile($vendorConfigDir . '/' . self::$mode . '.php');
        $vendorModules = $this->loadJsonFile($vendorConfigDir . '/modules.json');
        
        // Load user config
        $userCommonConfig = $this->loadJsonFile($userConfigDir . '/com.json');
        $userModeConfig = $this->loadJsonFile($userConfigDir . '/' . self::$mode . '.json');
        $userModules = $this->loadJsonFile($userConfigDir . '/modules.json');
        
        // Merge configurations
        $vendorConfig = array_merge_recursive($vendorModeConfig, $vendorCommonConfig);
        $userConfig = array_merge_recursive($userModeConfig, $userCommonConfig);
        $modules = array_merge_recursive($vendorModules, $userModules);
        self::$settings = array_replace_recursive($vendorConfig, $userConfig, $modules);
    }

    /**
     * Load a PHP configuration file.
     * @param string $filePath
     * @return array
     */
    private function loadConfigFile(string $filePath): array
    {
        return file_exists($filePath) ? require_once $filePath : [];
    }

    /**
     * Load a JSON configuration file.
     * @param string $filePath
     * @return array
     */
    private function loadJsonFile(string $filePath): array
    {
        return file_exists($filePath) ? json_decode(file_get_contents($filePath), true ) : [];
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
