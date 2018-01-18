<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';
require_once LIB_DIR . 'JWT/JWT.php';
require_once APPLICATION_PATH . 'config.php';

class AuthController extends BaseController
{
    private $_payload = [];

    public function loginAuth($username, $password)
    {
        $query = $this->queryHelper->select('*')
                                   ->from('users')
                                   ->where('username = ?')
                                   ->where('password = ?')
                                   ->setQuery();
        $match = $query->execQuery('numRows', 'ss', [
            $username,
            $password
        ]);

        if ($match === 1) {
            $result = $query->execQuery('getResult', 'ss', [
                $username,
                $password
            ]);
            $userInfo = $this->queryHelper->fetchData($result);
            $this->_payload['userInfo'] = [
                'id' => $userInfo->id,
                'username' => $userInfo->username,
                'name' => $userInfo->name,
                'dateOfBirth' => $userInfo->date_of_birth,
                'gender' => $userInfo->gender,
                'role' => $userInfo->role
            ];

            // Token
            return \Firebase\JWT\JWT::encode($this->_payload, Config::SECRET_KEY);
        }

        return null;
    }

    public static function verifyToken()
    {
        return false;
    }
}