<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

/**
 * Class RegisterController
 */
class RegisterController extends BaseController
{
    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if username is already in use.
     * @param $username
     * @return bool
     */
    public function checkUserExists($username)
    {
        $match = $this->queryHelper->select('*')
            ->from('users')
            ->where('username = ?')
            ->setQuery()
            ->execQuery('numRows', 's', [$username]);

        return ($match == 1);
    }

    /**
     * Create new user.
     * @param array $data
     * @return bool
     */
    public function createUser($data)
    {
        $status = $this->queryHelper->insert('users', [
            'username', 'password', 'name', 'date_of_birth', 'gender', 'role'
        ])->execQuery('crud', 'ssssii', [
            $data->username,
            $data->password,
            $data->name,
            (isset($data->dateOfBirth)) ? $data->dateOfBirth : null,
            (isset($data->gender)) ? $data->gender : null,
            0 // common user by default
        ]);

        return $status;
    }
}