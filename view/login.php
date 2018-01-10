<?php
if (isset($_POST['btnLogin'])) {
    $username = $controller->queryHelper->mRealEscapeString($_POST['username']);
    $password = md5($controller->queryHelper->mRealEscapeString($_POST['password']));
    $authController = new AuthController($baseInstance->queryHelper);
    $user = $authController->loginAuth($username, $password);
    if ($user) {
        header('Location:index.php');
    } else {
        $message = 'Tên Đăng Nhập hoặc Mật Khẩu không đúng!';
    }
}
?>
<div class="row login-section">
    <div class="col-md-5 div-center">
        <form method="POST" action="" class="login-form">
            <div class="form-group login-heading">
                <h4 class="text-center">Đăng Nhập Hệ Thống</h4>
            </div>

            <div class="form-group">
                <label for="username">Tên Đăng Nhập</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Tên Đăng nhập"
                       required oninvalid="this.setCustomValidity('Vui lòng Nhập Tên Đăng Nhập')"
                       oninput="setCustomValidity('')">
            </div>

            <div class="form-group">
                <label for="password">Mật Khẩu</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Mật Khẩu"
                       required oninvalid="this.setCustomValidity('Vui lòng Nhập Mật Khẩu')"
                       oninput="setCustomValidity('')">
            </div>

            <?php if (isset($message)): ?>
                <div class="alert alert-warning" role="alert">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="form-group text-center" data-ng-controller="registerController">
                <a href="javascript:void(0);" data-ng-click="openRegisterForm()">Đăng ký tài khoản</a>
            </div>

            <div class="form-group">
                <button type="submit" name="btnLogin" class="btn btn-secondary btn-login">Đăng Nhập</button>
            </div>
        </form>
    </div>
</div>
