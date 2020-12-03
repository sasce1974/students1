<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit30a3be7624dce5a7acc164425e81b902
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Core\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Core',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit30a3be7624dce5a7acc164425e81b902::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit30a3be7624dce5a7acc164425e81b902::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit30a3be7624dce5a7acc164425e81b902::$classMap;

        }, null, ClassLoader::class);
    }
}
