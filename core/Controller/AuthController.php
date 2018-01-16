<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';
require_once LIB_DIR . 'JWT/JWT.php';
require_once APPLICATION_PATH . 'config.php';

class AuthController extends BaseController
{
    private $_token = [];

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
            $this->_token['id'] = $userInfo->id;
            $this->_token['username'] = $userInfo->username;
            $this->_token['name'] = $userInfo->name;
            $this->_token['dateOfBirth'] = $userInfo->date_of_birth;
            $this->_token['gender'] = $userInfo->gender;
            $this->_token['role'] = $userInfo->role;

            $jsonWebToken = \Firebase\JWT\JWT::encode($this->_token, Config::SECRET_KEY);

            return $jsonWebToken;
        }

        return http_response_code(401);
    }

    public static function verifyToken()
    {
        return false;
    }
}