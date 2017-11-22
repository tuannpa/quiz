<?php

require_once(DRIVER_DIR . '/DB.php');

class ModelHelper extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
}