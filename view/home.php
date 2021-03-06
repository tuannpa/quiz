<?php
if (!is_bool($token = AuthController::verifyToken($_COOKIE['token']))):
    require_once CONTROLLER_DIR . 'HomeController.php';

    $homeController = new HomeController();
    $userInfo = AuthController::getUserInfo($token);
    if (isset($_SESSION['endOfTest'])):
        unset($_SESSION['endOfTest']);
    endif;
    $birthday = (!empty($userInfo->dateOfBirth)) ?
        date('d/m/Y', strtotime($userInfo->dateOfBirth)) : 'No data available..';
    $gender = ($userInfo->gender == 1) ? 'Male' : 'Female';
    ?>
    <div class="home" data-ng-controller="homeController">
        <h4>Hi, <?= $userInfo->name ?>!</h4>
        <hr/>
        <p><strong>Account information:</strong></p>

        <!-- cg-busy -->
        <form name="userForm"
              class="userForm"
              data-ng-submit="changePassword(userForm)"
              cg-busy="{promise:updatePasswordPromise,message:'Loading..',backdrop:true,minDuration:1000}"
              novalidate>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text"
                       class="form-control user-field"
                       value="<?= $userInfo->name ?>"
                       id="name"
                       disabled>
            </div>

            <div class="form-group">
                <label for="birthday">Date of birth</label>
                <input class="form-control user-field"
                       value="<?= $birthday ?>"
                       id="birthday"
                       disabled="">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <input class="form-control user-field"
                       value="<?= $gender ?>"
                       id="gender"
                       disabled="">
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input class="form-control user-field"
                       value="<?= $userInfo->username ?>"
                       id="username"
                       disabled="">
            </div>

            <div class="form-group">
                <label for="password">Change your password</label>
                <div>
                    <input type="password"
                           id="password"
                           class="form-control user-field on-same-line"
                           name="password"
                           data-ng-model="password"
                           required
                           data-ng-minlength="6"
                           data-ng-maxlength="20"
                           data-ng-class="{'got-errors' : userForm.$submitted && userForm.password.$invalid}">
                    <div data-ng-if="userForm.$submitted && userForm.password.$invalid" class="on-same-line">
                        <span data-ng-if="userForm.password.$error.minlength"
                              data-toggle="popover"
                              data-trigger="hover"
                              data-content="Password length is a minimum of 6 characters"
                              class="fa fa-exclamation-triangle warning-icon pointer-on-hover"
                              aria-hidden="true"></span>
                        <span data-ng-if="userForm.password.$error.maxlength"
                              data-toggle="popover"
                              data-trigger="hover"
                              data-content="Password length is a maximum of 20 characters"
                              class="fa fa-exclamation-triangle warning-icon pointer-on-hover"
                              aria-hidden="true"></span>
                        <span data-ng-if="userForm.password.$error.required"
                              data-toggle="popover"
                              data-trigger="hover"
                              data-content="Please enter new password"
                              class="fa fa-exclamation-triangle warning-icon pointer-on-hover"
                              aria-hidden="true"></span>
                    </div>
                </div>
            </div>

            <label for="password-again">Password Confirmation</label>
            <div class="input-group">
                <input type="password"
                       id="password-again"
                       name="passwordAgain"
                       class="form-control user-field"
                       data-ng-model="passwordAgain"
                       data-ng-class="{'got-errors' : userForm.$submitted && userForm.password.$invalid}"
                       data-compare-to="password">
                <div data-ng-if="userForm.$submitted"
                     class="on-same-line">
                    <span data-ng-show="userForm.passwordAgain.$error.compareTo"
                          data-toggle="popover"
                          data-trigger="hover"
                          data-placement="bottom"
                          data-content="Password confirmation does not match"
                          class="fa fa-exclamation-triangle warning-icon nxt-to-btn pointer-on-hover"
                          aria-hidden="true"></span>
                </div>
                <span class="input-group-btn">
				    <button class="btn btn-secondary pointer-on-hover"
                            type="submit">Update</button>
			    </span>
            </div>
        </form>

        <hr/>
        <h4>System action</h4>
        <button class="btn btn-primary pointer-on-hover fa fa-sign-out btn-sign-out" type="button" data-toggle="collapse"
                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Sign out
        </button>
        <div class="collapse logout-popup" id="collapseExample">
            <div class="card card-body">
                <div class="row" style="margin: 0 auto;">
                    <div class="col-md-12 reset-pd-left">
                        <label class="control-label">Are you sure you want to sign out?</label>
                    </div>

                    <div class="confirm-section">
                        <button onclick="window.location.href='?page=logout'" type="button"
                                class="btn btn-success btn-action btn-yes pointer-on-hover">Yes
                        </button>
                    </div>

                    <div class="confirm-section">
                        <button type="button" class="btn btn-danger btn-no btn-action pointer-on-hover">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
else:
    header('Location:?page=login');
endif;
?>