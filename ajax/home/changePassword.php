<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'HomeController.php';

if (!is_bool($token = AuthController::verifyToken($_COOKIE['token']))) {
    $controller = new HomeController();
    $params = HomeController::getRequestPayload();
    $userInfo = AuthController::getUserInfo($token);
    $status = $controller->updatePassword($userInfo->id, $params->password);
    $controller->jsonResponse([
        'status' => $status ? 'Success' : 'Error',
        'message' => $status ? 'Password has been updated.' : 'Fail to update password.'
    ]);
} else {
    HomeController::response(Config::UNAUTHORIZED_CODE, 'Unauthorized');
}
