<?php
if (isset($_POST['btnLogin'])):
    $authController = new AuthController($baseInstance->queryHelper);
    $token = $authController->loginAuth($_POST['username'], md5($_POST['password']));
    var_dump($token); die;
    if (is_null($token)):
        $message = 'Incorrect username or password';
    else:
        header('Location:index.php');
    endif;
endif;
?>
<div class="row login-section">
    <div class="col-md-5 div-center">
        <form method="POST" action="" class="login-form">
            <div class="form-group login-heading">
                <h4 class="text-center">Authentication</h4>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text"
                       class="form-control"
                       id="username"
                       name="username"
                       placeholder="Username"
                       required
                       oninvalid="this.setCustomValidity('Please enter username')"
                       oninput="setCustomValidity('')">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       id="password"
                       placeholder="Password"
                       required
                       oninvalid="this.setCustomValidity('Please enter password')"
                       oninput="setCustomValidity('')">
            </div>

            <?php if (isset($message)): ?>
                <div class="alert alert-warning" role="alert">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="form-group text-center"
                 data-ng-controller="registerController">
                <a href="javascript:void(0);"
                   data-ng-click="openRegisterForm()">Sign up</a>
            </div>

            <div class="form-group">
                <button type="submit" name="btnLogin" class="btn btn-secondary btn-login">Login</button>
            </div>
        </form>
    </div>
</div>
