<?php
/**
 * @author: Tuan Nguyen
 */

class Config
{
    const SECRET_KEY = 'a52bef76pu8t6';

    private static $config = [
        'host' => 'localhost',
        'dbName' => 'db_quiz',
        'username' => 'root',
        'password' => ''
    ];

    public static function getConfig() {
        return self::$config;
    }

    public static function loadDirectories()
    {
        if (!defined('CONTROLLER_DIR')) {
            $controllerDir = str_replace('\\', '/', __DIR__ . '/core/Controller' . DIRECTORY_SEPARATOR);
            define('CONTROLLER_DIR', $controllerDir);
        }

        if (!defined('DRIVER_DIR')) {
            $driverDir = str_replace('\\', '/', __DIR__ . '/core/DRIVER' . DIRECTORY_SEPARATOR);
            define('DRIVER_DIR', $driverDir);
        }

        if (!defined('HELPER_DIR')) {
            $helperDir = str_replace('\\', '/', __DIR__ . '/core/Helper' . DIRECTORY_SEPARATOR);
            define('HELPER_DIR', $helperDir);
        }

        if (!defined('LIB_DIR')) {
            $libDir = str_replace('\\', '/', __DIR__ . '/lib' . DIRECTORY_SEPARATOR);
            define('LIB_DIR', $libDir);
        }

        if (!defined('BLOCK_DIR')) {
            $blockDir = str_replace('\\', '/', __DIR__ . '/block' . DIRECTORY_SEPARATOR);
            define('BLOCK_DIR', $blockDir);
        }

        if (!defined('VIEW_DIR')) {
            $viewDir = str_replace('\\', '/', __DIR__ . '/view' . DIRECTORY_SEPARATOR);
            define('VIEW_DIR', $viewDir);
        }
    }
}