<?php
/**
 * @author: Tuan Nguyen
 */

require_once DRIVER_DIR . 'Database.php';
require_once APPLICATION_PATH . 'config.php';

/**
 * Class QueryHelper
 * The Class provides many methods for Database query.
 */
class QueryHelper extends Database
{
    /**
     * QueryHelper constructor.
     */
    public function __construct()
    {
        $config = Config::getConfig();
        parent::__construct($config['host'], $config['username'], $config['password'], $config['dbName']);
    }

    /**
     * Return a single record that has column 'id' matches with given id. The default column is 'id'
     * which is the primary key of the table, you can pass other value like 'category_id' or 'user_id', ...
     * Just make sure the actual value of the column name is type of integer.
     * @param string $table
     * @param integer $id
     * @param string $column
     * @return mixed
     */
    public function findById($table, $id, $column = 'id')
    {
        $query = $this->select('*')
            ->from($table)
            ->where($column . ' = ?')
            ->setQuery()
            ->execQuery('getResult', 'i', [$id]);

        return $this->fetchData($query);
    }

    /**
     * Return all records of the table.
     * @param string $table
     * @return mixed
     */
    public function all($table)
    {
        $query = $this->select('*')
            ->from($table)
            ->execQuery('getResult');

        return $this->fetchData($query);
    }
}