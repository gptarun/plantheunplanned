<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3a3589720fead77a02d5612938cb21b6
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

    public static $prefixesPsr0 = array (
        'o' => 
        array (
            'org\\bovigo\\vfs' => 
            array (
                0 => __DIR__ . '/..' . '/mikey179/vfsstream/src/main/php',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3a3589720fead77a02d5612938cb21b6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3a3589720fead77a02d5612938cb21b6::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3a3589720fead77a02d5612938cb21b6::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
