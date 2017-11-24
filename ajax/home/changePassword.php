<?php
require_once '../ajaxConfig.php';
$params = BaseController::getRequestParams();
$controller = new BaseController(new ModelHelper());
$currentUser = $controller->getUserInfo('users', ['id']);
$state = $controller->modelHelper
    ->update('users', [
        'password' => $params->password
    ])
    ->where([
        'id =' => $currentUser->id
    ])
    ->method('crud');

if ($state) {
    echo json_encode([
        'success' => true
    ]);
} else {
    echo json_encode([
        'success' => false
    ]);
}