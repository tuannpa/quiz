<?php
/**
 * @author: Tuan Nguyen
 */

require_once DRIVER_DIR . '/DB.php';
require_once APPLICATION_PATH . '/config.php';

class ModelHelper extends DB
{
    public function __construct()
    {
        $config = Config::getConfig();
        parent::__construct($config['serverName'], $config['username'], $config['password'], $config['dbName']);
    }
}