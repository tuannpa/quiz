<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'HomeController.php';
require_once CONTROLLER_DIR . 'AuthController.php';

$controller = new HomeController(new QueryHelper());

if (!is_bool($userInfo = AuthController::verifyToken())) {
    $params = HomeController::getRequestPayload();
    $state = $controller->queryHelper->update('users',['password'])
        ->where('id = ?')
        ->setQuery()
        ->execQuery('crud', 'si', [
            $params->password,
            $userInfo->id
        ]);
    $controller->jsonResponse(['status' => $state]);
} else {
    $controller->jsonResponse(['status' => false]);
}
