<?php
/**
 * @author: Tuan Nguyen
 */

require_once DRIVER_DIR . 'Database.php';
require_once APPLICATION_PATH . 'config.php';

class QueryHelper extends Database
{
    public function __construct()
    {
        $config = Config::getConfig();
        parent::__construct($config['serverName'], $config['username'], $config['password'], $config['dbName']);
    }

    public function findById($table, $id)
    {
        return $this->select()
            ->from($table)
            ->where([
                'id=' => $id
            ])
            ->method('one');
    }

    public function all($table)
    {
        return $this->select()
            ->from($table)
            ->method('many');
    }
}