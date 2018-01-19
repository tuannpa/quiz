<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';
require_once LIB_DIR . 'JWT/JWT.php';
require_once APPLICATION_PATH . 'config.php';

class AuthController extends BaseController
{
    /**
     * The payload contains user's information.
     * @var array
     */
    private $_payload = [];

    /**
     * The algorithm to encrypt the token.
     * @var string
     */
    private static $_algorithm = 'HS256';

    /**
     * Expired time for the token.
     * @var int
     */
    private static $_expireTime = 600;

    /**
     * Verify login credential, return token if username and password are correct.
     * @param $username
     * @param $password
     * @return null|string
     */
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

    /**
     * Check the token sent from user.
     * @return bool
     */
    public static function verifyToken()
    {
        if (isset($_COOKIE['token'])) {
            try {
                $token = \Firebase\JWT\JWT::decode($_COOKIE['token'], Config::SECRET_KEY, [self::$_algorithm]);
                return $token->userInfo;
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                if ($e->getMessage() === 'Signature verification failed') {
                    return false;
                }
            }

        }

        return false;
    }

    /**
     * Add the token to cookie.
     * @param $token
     * @return bool
     */
    public static function setAuthToken($token)
    {
        if (!is_null($token)){
            return setcookie('token', $token, time() + self::$_expireTime, '/');
        }

        return false;
    }


    public function logout()
    {
        $this->_payload = [];
        return setcookie('token', '', time() - self::$_expireTime, '/');
    }
}