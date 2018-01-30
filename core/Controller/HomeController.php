<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

/**
 * Class HomeController
 */
class HomeController extends BaseController
{
    /**
     * Update user password.
     * @param $userId
     * @param $password
     * @return mixed
     */
    public function updatePassword($userId, $password)
    {
        $state = $this->queryHelper->update('users', ['password'])
            ->where('id = ?')
            ->setQuery()
            ->execQuery('crud', 'si', [
                $password,
                $userId
            ]);

        return $this->jsonResponse(['status' => $state]);
    }
}