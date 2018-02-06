<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

/**
 * Class RegisterController
 */
class RegisterController extends BaseController
{
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

        if ($match == 1) {
            return true;
        }

        return false;
    }

    /**
     * Create new user.
     * @param array $data
     */
    public function createUser($data)
    {

    }
}