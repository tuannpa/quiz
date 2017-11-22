<?php

class Config
{
    public function __construct()
    {
        if (!defined('HOME_DIR')) {
            $home_dir = str_replace('\\', '/', __DIR__);
            define('HOME_DIR', $home_dir);
        }

        if (!defined('CONTROLLER_DIR')) {
            $core_dir = str_replace('\\', '/', __DIR__ . '/core/Controller');
            define('CONTROLLER_DIR', $core_dir);
        }

        if (!defined('DRIVER_DIR')) {
            $core_dir = str_replace('\\', '/', __DIR__ . '/core/DRIVER');
            define('DRIVER_DIR', $core_dir);
        }

        if (!defined('BLOCK_DIR')) {
            $block_dir = str_replace('\\', '/', __DIR__ . '/block');
            define('BLOCK_DIR', $block_dir);
        }

        if (!defined('VIEW_DIR')) {
            $view_dir = str_replace('\\', '/', __DIR__ . '/view');
            define('VIEW_DIR', $view_dir);
        }
    }
}