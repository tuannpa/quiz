<?php
/**
 * @author: Tuan Nguyen
 */

class Config
{
    const SERVER_NAME = 'localhost';
    const DB_NAME = 'db_quiz';
    const USERNAME = 'root';
    const PASSWORD = '';

    public static function getConfig() {
        return [
            'serverName' => self::SERVER_NAME,
            'dbName' => self::DB_NAME,
            'username' => self::USERNAME,
            'password' => self::PASSWORD
        ];
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