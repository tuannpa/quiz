<?php
session_start();
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'RegisterController.php';

$params = RegisterController::getRequestPayload();
if (AuthController::verifyCSRFToken($params->csrfToken)) {
    $controller = new RegisterController(new QueryHelper());
    if (!$controller->checkUserExists($params->username)) {
        $status = $controller->createUser($params);
        http_response_code(Config::STATUS_OK_CODE);
        $controller->jsonResponse([
            'status' => $status ? 'Success' : 'Error',
            'message' => $status ? 'Account has been successfully created.' : 'Fail to create new account.'
        ]);
    } else {
        http_response_code(Config::BAD_REQUEST_CODE);
        $controller->jsonResponse([
            'status' => 'Error',
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

