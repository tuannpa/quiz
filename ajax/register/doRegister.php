<?php
session_start();
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'RegisterController.php';

$params = RegisterController::getRequestPayload();
if (AuthController::verifyCSRFToken($params->csrfToken)) {
    $controller = new RegisterController(new QueryHelper());
    if (!$controller->checkUserExists($params->username)) {
        $controller->createUser($params);
    } else {
        $controller->jsonResponse([
            'message' => 'Username is already in use.'
        ]);
    }
} else {
    http_response_code(Config::BAD_REQUEST_CODE);
    RegisterController::Json([
        'statusCode' => Config::BAD_REQUEST_CODE,
        'message' => 'Bad request'
    ]);
}

