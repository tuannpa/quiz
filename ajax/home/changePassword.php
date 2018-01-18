<?php
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . '/HomeController.php';
$params = HomeController::getRequestPayload();
$controller = new HomeController(new QueryHelper());
$currentUser = $controller->getUserInfo('users');
// TODO: User ID, change $id when login authentication is done
$state = $controller->queryHelper->update('users',['password'])
                                 ->where('id = ?')
                                 ->setQuery()
                                 ->execQuery('crud', 'si', [
                                     $params->password,
                                     $id
                                 ]);
$controller->jsonResponse(['status' => $state]);