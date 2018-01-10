<?php
session_start();
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . '/HomeController.php';
$params = HomeController::getRequestParams();
$controller = new HomeController(new QueryHelper());
$currentUser = $controller->getUserInfo('users');
$state = $controller->queryHelper
    ->update('users', [
        'password' => $params->password
    ])
    ->where([
        'id =' => $currentUser->id
    ])
    ->method('crud');
$controller->jsonResponse(['status' => $state]);