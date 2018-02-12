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
     * Return a single record that has column 'id' matches with given id. The default value is 'id'
     * which is the primary key of the table, you can pass other value like 'category_id' or 'user_id', ...
     * Just make sure the actual value of the column is type of integer.
     * @param string $table
     * @param integer $id
     * @param string $column
     * @return mixed
     */
    public function findById($table, $id, $column = 'id')
    {
        $result = $this->select('*')
            ->from($table)
            ->where($column . ' = ?')
            ->setQuery()
            ->execQuery('getResult', 'i', [$id]);

        return $this->fetchData($result);
    }

    /**
     * Return all records of the table.
     * @param string $table
     * @param bool $getTotalRecord
     * @return mixed
     */
    public function all($table, $getTotalRecord = false)
    {
        $query = $this->select('*')
            ->from($table);

        if ($getTotalRecord) {
            return $query->execQuery('numRows');
        }
        $result = $query->execQuery('getResult');

        return $this->fetchData($result);
    }

    /**
     * This method is created for the purpose to work with raw queries.
     * ******************************
     * For example:
     *  $this->unprepared('SELECT * FROM {tableName}');
     * ******************************
     * @param $query
     * @return array
     */
    public function unprepared($query)
    {
        if ($this->mNumRows($query) > 1) {
            return $this->mFetchAssoc($query);
        }

        return $this->mFetchAssocOne($query);
    }
}