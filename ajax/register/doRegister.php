<?php
session_start();
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'RegisterController.php';

$params = RegisterController::getRequestPayload();
if (AuthController::verifyCSRFToken($params->csrfToken)) {
    $controller = new RegisterController();
    if (!$controller->checkUserExists($params->username)) {
        $status = $controller->createUser($params);
        RegisterController::setHttpResponseCode(Config::STATUS_OK_CODE);
        $controller->jsonResponse([
            'status' => $status ? 'Success' : 'Error',
            'message' => $status ? 'Account has been successfully created.' : 'Fail to create new account.'
        ]);
    } else {
        RegisterController::setHttpResponseCode(Config::BAD_REQUEST_CODE);
        $controller->jsonResponse([
            'status' => 'Error',
            'message' => 'Username is already in use.'
        ]);
    }
} else {
    RegisterController::response(Config::UNAUTHORIZED_CODE, 'Unauthorized');
}

