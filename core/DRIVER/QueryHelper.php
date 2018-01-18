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
        parent::__construct($config['host'], $config['username'], $config['password'], $config['dbName']);
    }

    public function findById($table, $id, $column = 'id')
    {
        $query = $this->select('*')
                      ->from($table)
                      ->where($column . ' = ?')
                      ->setQuery()
                      ->execQuery('getResult', 'i', [$id]);

        return $this->fetchData($query);
    }

    public function all($table)
    {
        $query = $this->select('*')
                      ->from($table)
                      ->execQuery('getResult');

        return $this->fetchData($query);
    }
}