<?php
/**
 * @author: Tuan Nguyen
 */

ob_start();
session_start();
if (!defined('APPLICATION_PATH')) {
    $homeDir = str_replace('\\', '/', __DIR__ . DIRECTORY_SEPARATOR);
    define('APPLICATION_PATH', $homeDir);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8"/>
    <title>Quiz System</title>
    <link rel="stylesheet" type="text/css" href="public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/bootstrap/bootstrap-reboot.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/style/style.css">
    <link rel="stylesheet" type="text/css" href="public/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/angular-busy/angular-busy.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/angular-toaster/toaster.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/angular-ngDialog/ngDialog.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/angular-ngDialog/ngDialog-theme-default.min.css">
</head>
<body data-ng-app="myApp">
    <div class="container">
        <?php
        require_once APPLICATION_PATH . 'config.php';
        Config::loadDirectories();
        require_once CONTROLLER_DIR . 'Base/BaseController.php';
        require_once CONTROLLER_DIR . 'AuthController.php';
        $baseInstance = new BaseController(new QueryHelper());
        $authController = new AuthController($baseInstance->queryHelper);
        include BLOCK_DIR . 'header.php';
        include BLOCK_DIR . 'menu.php';
        $urlParams = $baseInstance->getUrlParams();
        $page = isset($urlParams->page) ? $urlParams->page : 'home';
        include VIEW_DIR . $page . '.php';
        include BLOCK_DIR . 'footer.php';
        ?>
    </div>
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
    <script src="public/js/angular-ngDialog/ngDialog.min.js"></script>
    <script src="public/js/project/appModule.js"></script>
    <script src="public/js/project/homeController.js"></script>
    <script src="public/js/project/quizController.js"></script>
    <script src="public/js/project/registerController.js"></script>
    <script type="text/ng-template" id="registerForm">
        <div class="modal-header text-center register-heading">
            <h5 class="modal-title">Đăng Ký Tài Khoản</h5>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="name">Họ và Tên</label>
                <input type="text" class="form-control user-field" id="name">
            </div>

            <div class="form-group">
                <label for="name">Ngày sinh</label>
                <input type="text" class="form-control user-field" id="name">
            </div>

            <div class="form-group">
                <label for="name">Giới tính</label>
                <input type="text" class="form-control user-field" id="name">
            </div>

            <div class="form-group">
                <label for="name">Tên tài khoản</label>
                <input type="text" class="form-control user-field" id="name">
            </div>

            <div class="form-group">
                <label for="name">Mật Khẩu</label>
                <input type="text" class="form-control user-field" id="name">
            </div>
        </div>
        <div class="ngdialog-buttons">
            <button type="button" class="ngdialog-button ngdialog-button-primary" data-ng-click="">Open next</button>
        </div>
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.cg-busy-default-wrapper').addClass('loading-change-password');
            $('.cg-busy-default-wrapper').addClass('question-loading');
            $('.btn-no').on('click', function () {
                $('.logout-popup').collapse('hide');
            });
        });
    </script>
</body>
</html>
