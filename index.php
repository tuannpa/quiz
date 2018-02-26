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
    <link rel="stylesheet" type="text/css" href="public/css/angular-datetimepicker/datetimepicker.css">
</head>
<body data-ng-app="myApp">
<div class="container">
    <?php
    require_once APPLICATION_PATH . 'config.php';
    Config::loadDirectories();
    require_once CONTROLLER_DIR . 'AuthController.php';
    AuthController::initCSRFToken();
    AuthController::initToken();
    $authController = new AuthController();
    include BLOCK_DIR . 'header.php';
    include BLOCK_DIR . 'menu.php';
    $urlParams = $authController->getUrlParams();
    $page = isset($urlParams->page) ? $urlParams->page : 'home';
    include VIEW_DIR . $page . '.php';
    include BLOCK_DIR . 'footer.php';
    ?>
    <!-- Toaster Notification -->
    <toaster-container
            toaster-options="{'position-class': 'toast-bottom-right', 'close-button':true, 'animation-class': 'toast-bottom-right'}"></toaster-container>
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
<script src="public/js/moment/moment.min.js"></script>
<script src="public/js/angular-datetimepicker/angular-datetimepicker.js"></script>
<script src="public/js/angular-datetimepicker/angular-datetimepicker-template.js"></script>
<script src="public/js/angular-datetimeinput/angular-datetimeinput.js"></script>
<script src="public/js/project/appModule.js"></script>
<script src="public/js/project/helper/utils.js"></script>
<script src="public/js/project/homeController.js"></script>
<script src="public/js/project/quizController.js"></script>
<script src="public/js/project/registerController.js"></script>
<script type="text/ng-template" id="registerForm">
    <div class="modal-header text-center register-heading">
        <h5 class="modal-title">Sign up</h5>
    </div>
    <form name="registerForm"
          data-ng-submit="doRegister(registerForm)"
          cg-busy="{promise:registerPromise,message:'Loading..',backdrop:true, minDuration:1000, wrapperClass:'user-register'}"
          data-ng-init="csrfToken = '<?= $_SESSION['CSRFToken'] ?>'"
          novalidate>
        <div class="modal-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text"
                       name="name"
                       ng-model="name"
                       class="form-control"
                       id="name"
                       required>
                <small data-ng-if="registerForm.$submitted && registerForm.name.$invalid" class="text-danger">
                    <span class="fa fa-exclamation-triangle"
                          data-ng-if="registerForm.name.$error.required">This field is required</span>
                </small>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text"
                       name="username"
                       ng-model="username"
                       class="form-control"
                       id="username"
                       required
                       data-ng-maxlength="20">
                <small data-ng-if="registerForm.$submitted && registerForm.username.$invalid"
                       class="text-danger validation-message">
                    <span class="fa fa-exclamation-triangle"
                          data-ng-if="registerForm.username.$error.required">This field is required</span>
                    <span class="fa fa-exclamation-triangle"
                          data-ng-if="registerForm.username.$error.maxlength">Username length is a maximum of 20 characters</span>
                </small>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password"
                       name="password"
                       ng-model="password"
                       class="form-control"
                       id="password"
                       required
                       data-ng-minlength="6"
                       data-ng-maxlength="20">
                <small data-ng-if="registerForm.$submitted && registerForm.password.$invalid"
                       class="text-danger validation-message">
                    <span class="fa fa-exclamation-triangle"
                          data-ng-if="registerForm.password.$error.required">This field is required</span>
                    <span class="fa fa-exclamation-triangle"
                          data-ng-if="registerForm.password.$error.minlength">Password length is a minimum of 6 characters</span>
                    <span class="fa fa-exclamation-triangle"
                          data-ng-if="registerForm.password.$error.maxlength">Password length is a maximum of 20 characters</span>
                </small>
            </div>

            <div class="form-group">
                <label for="passwordConfirm">Password confirmation</label>
                <input type="password"
                       name="passwordConfirm"
                       ng-model="passwordConfirm"
                       class="form-control"
                       id="passwordConfirm"
                       data-compare-to="password">
                <small data-ng-if="registerForm.$submitted && registerForm.passwordConfirm.$invalid"
                       class="text-danger validation-message">
                    <span class="fa fa-exclamation-triangle"
                          data-ng-show="registerForm.passwordConfirm.$error.compareTo">Password confirmation does not match</span>
                </small>
            </div>

            <div class="form-group">
                <label for="dateOfBirth">Date of birth</label>
                <div class="dropdown dropdown1-parent">
                    <a class="dropdown-toggle dob-toggle"
                       id="datetimepicker-dropdown"
                       role="button"
                       data-toggle="dropdown"
                       data-target=".dropdown1-parent" href="#">
                        <div class="input-group">
                            <input type="text"
                                   data-date-time-input="DD/MM/YYYY"
                                   id="dateOfBirth"
                                   name="dateOfBirth"
                                   class="form-control"
                                   data-ng-model="dateOfBirth">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <datetimepicker data-ng-model="dateOfBirth"
                                        data-on-set-time="onTimeSet(newDate, oldDate)"
                                        data-datetimepicker-config="{ dropdownSelector: '.dob-toggle', minView: 'day' }"></datetimepicker>
                    </ul>

                </div>
            </div>

            <div>
                <label>Gender</label>
            </div>

            <label class="custom-control custom-radio">
                <input name="gender"
                       type="radio"
                       class="custom-control-input"
                       ng-model="gender"
                       value="1">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Male</span>
            </label>
            <label class="custom-control custom-radio">
                <input id="mixed0"
                       name="gender"
                       type="radio"
                       class="custom-control-input"
                       ng-model="gender"
                       value="0">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Female</span>
            </label>
        </div>
        <div class="ngdialog-buttons d-flex justify-content-center">
            <button type="submit"
                    class="ngdialog-button ngdialog-button-primary">Register
            </button>
        </div>
    </form>

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
