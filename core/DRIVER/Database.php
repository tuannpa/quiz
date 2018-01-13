<?php
/**
 * @author: Tuan Nguyen
 */

class Database
{
    /**
     * @var mysqli $_conn
     */
    private $_conn;

    /**
     * @var string $_sql
     */
    private $_sql = '';

    /**
     * @var array $where
     */
    private $where = [];

    /**
     * Database constructor, contains database credentials.
     * @param $host
     * @param $username
     * @param $password
     * @param $dbName
     */
    public function __construct($host, $username, $password, $dbName)
    {
        if (!$this->_conn) {
            $this->_conn = new mysqli($host, $username, $password, $dbName);
            $this->mQuery("SET NAMES 'utf8'");

            if ($this->_conn->connect_error) {
                die('Connection failed: ' . $this->_conn->connect_error);
            }
        }
    }

    /**
     * @param $query
     * @return bool|mysqli_result
     */
    public function mQuery($query)
    {
        return $this->_conn->query($query);
    }

    /**
     * Escape unexpected characters.
     * @param $str
     * @return string
     */
    public function mRealEscapeString($str)
    {
        return $this->_conn->real_escape_string($str);
    }

    /**
     * Return single record.
     * @param $query
     * @return array
     */
    public function mFetchAssocOne($query)
    {
        return $this->mQuery($query)->fetch_assoc();
    }

    /**
     * Return multiple records.
     * @param $query
     * @return array
     */
    public function mFetchAssoc($query)
    {
        if ($results = $this->mQuery($query)) {
            while ($row = $results->fetch_assoc()) {
                $arr[] = $row;
            }
        }
        $results->free();
        return $arr;
    }

    /**
     * Return the number of records from executed query.
     * @param $query
     * @return int
     */
    public function mNumRows($query)
    {
        $results = $this->mQuery($query);
        return $results->num_rows;
    }

    /**
     * Insert method, return $this to allow chaining.
     * @param $table
     * @param array $field
     * @return $this
     */
    public function insert($table, $field = [])
    {
        $columnList = '';
        $valueList = '';
        foreach ($field as $key => $value) {
            $columnList .= ',' . $key;
            $valueList .= ",'" . $this->mRealEscapeString($value) . "'";
        }

        $this->_sql = 'INSERT INTO ' . $table . '(' . trim($columnList, ',') . ') VALUES (' . trim($valueList, ',') . ')';
        return $this;
    }

    /**
     * Update method, return $this to allow chaining.
     * @param $table
     * @param array $field
     * @return $this
     */
    public function update($table, $field = [])
    {
        $columnValue = '';
        foreach ($field as $key => $value) {
            $columnValue .= $key . " = '" . $this->mRealEscapeString($value) . "',";
        }
        $this->_sql = 'UPDATE ' . $table . ' SET ' . trim($columnValue, ',');
        return $this;
    }

    /**
     * Delete method, return $this to allow chaining.
     * @return $this
     */
    public function delete()
    {
        $this->_sql = 'DELETE';
        return $this;
    }

    /**
     * Select method, return $this to allow chaining.
     * @param $columns
     * @return $this
     */
    public function select($columns)
    {
        $colName = null;
        if (!empty(func_get_args())) {
            foreach (func_get_args() as $column) {
                $colName .= ',' . $column;
            }
        }
        $colName = (!is_null($colName)) ? ltrim($colName, ',') : '*';
        $this->_sql = 'SELECT ' . $colName;

        return $this;
    }

    /**
     * From method, return $this to allow chaining.
     * @param $tables
     * @return $this
     */
    public function from($tables)
    {
        if (func_num_args() > 1) {
            $tbl = null;
            foreach (func_get_args() as $table) {
                $tbl .= ',' . $table;
            }
        }
        $tableName = (!is_null($tbl)) ? ltrim($tbl, ',') : $tables;
        $this->_sql .= ' FROM ' . $tableName;

        return $this;
    }

    /**
     * Where method, return $this to allow chaining.
     * @param $conditions
     * @param string $glue
     * @return $this
     */
    public function where($conditions, $glue = 'AND')
    {
        if (empty($this->where)) {
            $this->append(' WHERE');
            $this->append($conditions);
        } else {
            $this->append($glue);
            $this->append($conditions);
        }

        return $this;
    }

    public function orderBy($field = [])
    {
        $colName = '';
        foreach ($field as $value) {
            $colName .= ',' . $value;
        }
        $this->_sql .= ' ORDER BY ' . ltrim($colName, ',');
        return $this;
    }

    public function limit($startPosition, $numOfRecords)
    {
        $this->_sql .= ' LIMIT ' . $startPosition . ', ' . $numOfRecords;
        return $this;
    }

    /**
     * Append where conditions.
     * @param $element
     */
    public function append($element)
    {
        if (!is_array($element)) {
            $this->where = array_merge($this->where, [$element]);
        } else {
            $this->where = array_merge($this->where, $element);
        }
    }

    /**
     * Prepare Query.
     * @param $query
     * @return mysqli_stmt
     */
    public function prepareQuery($query)
    {
        return $this->_conn->prepare($query);
    }

    /**
     * Execute Query.
     * @param $command
     * @param null $dataType
     * @param array $values
     * @return bool|int|mysqli_result
     */
    public function execQuery($command, $dataType = null, $values = [])
    {
        $stmt = $this->prepareQuery($this->_sql);
        $stmt->bind_param($dataType, ...$values);
        switch ($command) {
            case 'crud':
                return $stmt->execute();
                break;
            case 'getResult':
                $stmt->execute();
                return $stmt->get_result();
                break;
            case 'numRows':
                $stmt->execute();
                $stmt->store_result();
                return $stmt->num_rows;
        }
    }

    /**
     * Fetch data from given result.
     * @param $result
     * @param bool $getArray
     * @return array
     */
    public function fetchData($result, $getArray = false)
    {
        if ($result->num_rows > 1) {
            while ($row = (!$getArray) ? $result->fetch_object() : $result->fetch_assoc()) {
                $records[] = $row;
            }
            return $records;
        }

        return (!$getArray) ? $result->fetch_object() : $result->fetch_assoc();
    }

    /**
     * Database Destructor.
     */
    public function __destruct()
    {
        if ($this->_conn) {
            $this->_conn->close();
        }
    }
}




