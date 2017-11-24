<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trắc nghiệm trực tuyến</title>
    <link rel="stylesheet" type="text/css" href="public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/bootstrap/bootstrap-reboot.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/style/style.css">
    <link rel="stylesheet" type="text/css" href="public/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/angular-busy/angular-busy.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/angular-toaster/toaster.min.css">

    <script src="public/js/jquery/jquery.min.js"></script>
    <script src="public/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="public/js/bootstrap/bootstrap.min.js"></script>
    <script src="public/js/angular1.6/angular.min.js"></script>
    <script src="public/js/angular-md5/angular-md5.min.js"></script>
    <script src="public/js/angular-busy/angular-busy.min.js"></script>
    <script src="public/js/angular-animate/angular-animate.min.js"></script>
    <script src="public/js/angular-toaster/toaster.min.js"></script>
    <script src="public/js/angular-sanitize/angular-sanitize.js"></script>
    <script src="public/js/angular-local-storage/angular-local-storage.min.js"></script>
    <script src="public/js/project/homeController.js"></script>
    <script src="public/js/project/quizController.js"></script>
</head>
<body>
    <div class="container" data-ng-app="myApp">
        <?php
        session_start();
        if (!defined('APPLICATION_PATH')) {
            $homeDir = str_replace('\\', '/', __DIR__);
            define('APPLICATION_PATH', $homeDir);
        }
        require_once APPLICATION_PATH . '/config.php';
        Config::getDir();
        require_once CONTROLLER_DIR . '/BaseController.php';
        $controller = new BaseController(new ModelHelper());
        include BLOCK_DIR . '/header.php';
        include BLOCK_DIR . '/menu.php';
        $urlParams = $controller->getUrlParams();
        isset($urlParams->page) ? $page = $urlParams->page : $page = 'home';
        include VIEW_DIR . '/' . $page . '.php';
        include BLOCK_DIR . '/footer.php';
        ?>
    </div>
</body>
</html>
