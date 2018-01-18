<?php
if (!defined('APPLICATION_PATH')) {
    $appDir = str_replace('\\', '/', dirname(__DIR__) . DIRECTORY_SEPARATOR);
    define('APPLICATION_PATH', $appDir);
}
require_once APPLICATION_PATH . 'config.php';
Config::loadDirectories();
