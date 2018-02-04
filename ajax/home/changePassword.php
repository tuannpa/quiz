<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'HomeController.php';

if (!is_bool($token = AuthController::verifyToken($_COOKIE['token']))) {
    $controller = new HomeController(new QueryHelper());
    $params = HomeController::getRequestPayload();
    $userInfo = AuthController::getUserInfo($token);
    $controller->updatePassword($userInfo->id, $params->password);
} else {
    http_response_code(Config::UNAUTHORIZED_CODE);
    HomeController::Json([
        'statusCode' => Config::UNAUTHORIZED_CODE,
        'message' => 'Unauthorized'
    ]);
}
