<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7774f98aa60ba14a8d248e61982ea5a3
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zana\\' => 5,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zana\\' => 
        array (
            0 => __DIR__ . '/..' . '/zana',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7774f98aa60ba14a8d248e61982ea5a3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7774f98aa60ba14a8d248e61982ea5a3::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit7774f98aa60ba14a8d248e61982ea5a3::$fallbackDirsPsr4;
            $loader->classMap = ComposerStaticInit7774f98aa60ba14a8d248e61982ea5a3::$classMap;

        }, null, ClassLoader::class);
    }
}
