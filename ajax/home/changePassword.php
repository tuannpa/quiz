<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'HomeController.php';
require_once CONTROLLER_DIR . 'AuthController.php';

$controller = new HomeController(new QueryHelper());

if (!is_bool($decryptedToken = AuthController::verifyToken())) {
    $params = HomeController::getRequestPayload();
    $userInfo = $decryptedToken->userInfo;
    $state = $controller->queryHelper->update('users',['password'])
                                     ->where('id = ?')
                                     ->setQuery()
                                     ->execQuery('crud', 'si', [
                                         $params->password,
                                         $userInfo->id
                                     ]);
    $controller->jsonResponse(['status' => $state]);
} else {
    http_response_code(Config::STATUS_CODE);
}
