<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

class AuthController extends BaseController
{
    public function loginAuth($username, $password)
    {
        $numOfRow = $this->queryHelper
            ->select()
            ->from('users')
            ->where([
                'username =' => $username,
                'password =' => $password
            ])
            ->method('numRows');
        $userInfo = $this->queryHelper
            ->select()
            ->from('users')
            ->where([
                'username =' => $username,
                'password =' => $password
            ])
            ->method('one');

        $_SESSION['user'] = ($numOfRow == 1) ? $userInfo : [];
        return $_SESSION['user'];
    }

    public static function hasSignedIn()
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
    }

    public static function logout()
    {
        if (self::hasSignedIn()) {
            unset($_SESSION['user']);
            header('Location: index.php');
        } else {
            header('Location: ?page=login');
        }
    }
}