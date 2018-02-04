<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'RegisterController.php';

$controller = new RegisterController(new QueryHelper());
$params = RegisterController::getRequestPayload();
var_dump($params);
