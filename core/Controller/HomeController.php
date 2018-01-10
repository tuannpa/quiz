<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

class HomeController extends BaseController
{
    public function getUserInfo($table)
    {
        $this->currentUser = $this->queryHelper->findById($table, $_SESSION['user']['id']);
        return $this->toObject($this->currentUser);
    }
}