<?php
if (!defined('APPLICATION_PATH')) {
    $appDir = str_replace('\\', '/', dirname(__DIR__));
    define('APPLICATION_PATH', $appDir);
}
require_once APPLICATION_PATH . '/config.php';
Config::getDir();
require_once CONTROLLER_DIR . '/BaseController.php';
