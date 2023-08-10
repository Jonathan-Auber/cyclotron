<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb64fc57bd185c1fed2a4b09d6a9b8b43
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb64fc57bd185c1fed2a4b09d6a9b8b43::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb64fc57bd185c1fed2a4b09d6a9b8b43::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb64fc57bd185c1fed2a4b09d6a9b8b43::$classMap;

        }, null, ClassLoader::class);
    }
}
