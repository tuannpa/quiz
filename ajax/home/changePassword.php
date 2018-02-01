<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'HomeController.php';
require_once CONTROLLER_DIR . 'AuthController.php';

$controller = new HomeController(new QueryHelper());

if (!is_bool($token = AuthController::verifyToken())) {
    $params = HomeController::getRequestPayload();
    $userInfo = AuthController::getUserInfo($token);
    $controller->updatePassword($userInfo->id, $params->password);
} else {
    http_response_code(Config::STATUS_CODE);
}
