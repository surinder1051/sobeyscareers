<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba741793430b2cd3131fd03be92cc447
{
    public static $prefixesPsr0 = array (
        'v' => 
        array (
            'vierbergenlars\\SemVer\\' => 
            array (
                0 => __DIR__ . '/..' . '/vierbergenlars/php-semver/src',
            ),
            'vierbergenlars\\LibJs\\' => 
            array (
                0 => __DIR__ . '/..' . '/vierbergenlars/php-semver/src',
            ),
        ),
    );

    public static $classMap = array (
        'vierbergenlars\\SemVer\\Internal\\Comparator' => __DIR__ . '/..' . '/vierbergenlars/php-semver/src/vierbergenlars/SemVer/internal.php',
        'vierbergenlars\\SemVer\\Internal\\Exports' => __DIR__ . '/..' . '/vierbergenlars/php-semver/src/vierbergenlars/SemVer/internal.php',
        'vierbergenlars\\SemVer\\Internal\\G' => __DIR__ . '/..' . '/vierbergenlars/php-semver/src/vierbergenlars/SemVer/internal.php',
        'vierbergenlars\\SemVer\\Internal\\Range' => __DIR__ . '/..' . '/vierbergenlars/php-semver/src/vierbergenlars/SemVer/internal.php',
        'vierbergenlars\\SemVer\\Internal\\SemVer' => __DIR__ . '/..' . '/vierbergenlars/php-semver/src/vierbergenlars/SemVer/internal.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitba741793430b2cd3131fd03be92cc447::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitba741793430b2cd3131fd03be92cc447::$classMap;

        }, null, ClassLoader::class);
    }
}
