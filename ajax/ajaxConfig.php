<?php

if (!defined('APPLICATION_PATH')) {
    $appDir = str_replace('\\', '/', dirname(__DIR__) . DIRECTORY_SEPARATOR);
    define('APPLICATION_PATH', $appDir);
}
require_once APPLICATION_PATH . 'config.php';
Config::loadDirectories();

require_once CONTROLLER_DIR . 'AuthController.php';
if (is_bool(AuthController::verifyToken($_COOKIE['token']))) {
    http_response_code(Config::UNAUTHORIZED_CODE);
}
