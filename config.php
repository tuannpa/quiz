<?php
/**
 * @author: Tuan Nguyen
 */

class Config
{
    private static $config = [
        'serverName' => 'localhost',
        'dbName' => 'db_quiz',
        'username' => 'root',
        'password' => ''
    ];

    public static function getConfig() {
        return self::$config;
    }

    public static function getDir()
    {
        if (!defined('CONTROLLER_DIR')) {
            $controllerDir = str_replace('\\', '/', __DIR__ . '/core/Controller');
            define('CONTROLLER_DIR', $controllerDir);
        }

        if (!defined('DRIVER_DIR')) {
            $driverDir = str_replace('\\', '/', __DIR__ . '/core/DRIVER');
            define('DRIVER_DIR', $driverDir);
        }

        if (!defined('HELPER_DIR')) {
            $helperDir = str_replace('\\', '/', __DIR__ . '/core/Helper');
            define('HELPER_DIR', $helperDir);
        }

        if (!defined('BLOCK_DIR')) {
            $blockDir = str_replace('\\', '/', __DIR__ . '/block');
            define('BLOCK_DIR', $blockDir);
        }

        if (!defined('VIEW_DIR')) {
            $viewDir = str_replace('\\', '/', __DIR__ . '/view');
            define('VIEW_DIR', $viewDir);
        }
    }
}