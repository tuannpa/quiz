<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

/**
 * Class HomeController
 */
class HomeController extends BaseController
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Update user password.
     * @param $userId
     * @param $password
     * @return mixed
     */
    public function updatePassword($userId, $password)
    {
        $status = $this->queryHelper->update('users', ['password'])
            ->where('id = ?')
            ->setQuery()
            ->execQuery('crud', 'si', [
                $password,
                $userId
            ]);

        return $status;
    }
}