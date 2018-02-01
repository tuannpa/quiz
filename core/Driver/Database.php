<?php
/**
 * @author: Tuan Nguyen
 */

/**
 * Class Database
 */
abstract class Database
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
     * @var array $_where
     */
    private $_where = [];

    /**
     * @var array $_orderBy
     */
    private $_orderBy = [];

    /**
     * @var array $_limit
     */
    private $_limit = [];

    /**
     * @var array $_join
     */
    private $_join = [];

    /**
     * @var array $_join
     */
    private $_on = [];

    /**
     * @var array $_groupBy
     */
    private $_groupBy = [];

    /**
     * @var array $_having
     */
    private $_having = [];

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
     * Insert method, returns $this to allow chaining.
     * @param $table
     * @param array $columns
     * @param string $param
     * @return $this
     */
    public function insert($table, $columns = [], $param = '?')
    {
        $columnList = null;
        $params = null;
        foreach ($columns as $column) {
            $columnList .= ',' . $column;
            $params .= ', ' . $param;
        }
        $this->_sql = 'INSERT INTO ' . $table . '(' . ltrim($columnList, ',') . ') VALUES (' . ltrim($params, ',') . ')';

        return $this;
    }

    /**
     * Update method, returns $this to allow chaining.
     * @param $table
     * @param array $columns
     * @param string $param
     * @return $this
     */
    public function update($table, $columns = [], $param = '?')
    {
        $columnList = null;
        foreach ($columns as $column) {
            $columnList .= $column . ' = ' . $param . ', ';
        }
        $this->_sql = 'UPDATE ' . $table . ' SET ' . rtrim($columnList, ', ');

        return $this;
    }

    /**
     * Delete method, returns $this to allow chaining.
     * @return $this
     */
    public function delete()
    {
        $this->_sql = 'DELETE';
        return $this;
    }

    /**
     * Select method, returns $this to allow chaining.
     * @param $columns
     * @return $this
     */
    public function select($columns)
    {
        $this->_sql = 'SELECT ' . $this->getArgs(func_get_args());
        return $this;
    }

    /**
     * From method, returns $this to allow chaining.
     * @param $tables
     * @return $this
     */
    public function from($tables)
    {
        $this->_sql .= ' FROM ' . $this->getArgs(func_get_args());
        return $this;
    }

    /**
     * Where method, returns $this to allow chaining.
     * @param $conditions
     * @param string $glue
     * @return $this
     */
    public function where($conditions, $glue = 'AND')
    {
        if (empty($this->_where)) {
            $this->append(' WHERE', '_where');
            $this->append($conditions, '_where');
        } else {
            $this->append(strtoupper($glue), '_where');
            $this->append($conditions, '_where');
        }

        return $this;
    }

    /**
     * OrderBy method, returns $this to allow chaining.
     * @param $columns
     * @return $this
     */
    public function orderBy($columns)
    {
        if (empty($this->_orderBy)) {
            $this->_orderBy[] = ' ORDER BY ';
        }
        $i = 1;
        foreach (func_get_args() as $column) {
            $this->_orderBy[] = ($i > 1) ? ', ' . $column : $column;
            $i++;
        }

        return $this;
    }

    /**
     * Limit method, returns $this to allow chaining.
     * @param $limit
     * @param null $offset
     * @return $this
     */
    public function limit($limit, $offset = null)
    {
        if (empty($this->_limit)) {
            $this->_limit[] = ' LIMIT ';
        }

        if (!is_null($offset)) {
            $this->_limit[] = $offset . ', ' . $limit;
        } else {
            $this->_limit[] = $limit;
        }

        return $this;
    }

    /**
     * Join method, return $this to allow chaining.
     * @param $table
     * @param $type
     * @return $this
     */
    public function join($table, $type)
    {
        switch (strtoupper($type)) {
            case 'LEFT':
                $this->_join[] = ' LEFT JOIN ' . $table;
                break;
            case 'RIGHT':
                $this->_join[] = ' RIGHT JOIN ' . $table;
                break;
            case 'FULL OUTER':
                $this->_join[] = ' FULL OUTER JOIN ' . $table;
                break;
            default:
                $this->_join[] = ' INNER JOIN ' . $table;
                break;
        }

        return $this;
    }

    /**
     * Join condition, return $this to allow chaining.
     * @param $condition
     * @return $this
     */
    public function on($condition)
    {
        $this->_on[] = ' ON ' . $condition;
        return $this;
    }

    /**
     * Group by method, return $this to allow chaining.
     * @param $columns
     * @return $this
     */
    public function groupBy($columns)
    {
        $this->_groupBy[] = ' GROUP BY ';

        if (func_num_args() > 1) {
            $this->_groupBy[] = $this->getArgs(func_get_args());
        } else {
            $this->_groupBy[] = $columns;
        }

        return $this;
    }

    /**
     * Having method, used to support usages of aggregate functions,
     * return $this to allow chaining.
     * @param $condition
     * @param string $glue
     * @return $this
     */
    public function having($condition, $glue = 'AND')
    {
        if (empty($this->_having)) {
            $this->append(' HAVING', '_having');
            $this->append($condition, '_having');
        } else {
            $this->append(strtoupper($glue), '_having');
            $this->append($condition, '_having');
        }

        return $this;
    }

    /**
     * Append conditions, identified by property name.
     * @param $element
     * @param string $property
     */
    public function append($element, $property)
    {
        if (!is_array($element)) {
            $this->{$property} = array_merge($this->{$property}, [$element]);
        } else {
            $this->{$property} = array_merge($this->{$property}, $element);
        }
    }

    /**
     * @param array $args
     * @param null $argBag
     * @return string
     */
    public function getArgs($args, $argBag = null)
    {
        if (count($args) > 1) {
            foreach ($args as $arg) {
                $argBag .= ',' . $arg;
            }
        }

        return (!is_null($argBag)) ? ltrim($argBag, ',') : current($args);
    }

    /**
     * Build complete query.
     * @return $this
     */
    public function setQuery()
    {
        if (!empty($this->_join) && !empty($this->_on)) {
            foreach ($this->_join as $k => $v) {
                $this->_sql .= $v . $this->_on[$k];
            }
            $this->_join = [];
            $this->_on = [];
        }

        if (!empty($this->_where)) {
            $this->_sql .= $this->parseSQL($this->_where, ' ');
            $this->_where = [];
        }

        if (!empty($this->_groupBy)) {
            $this->_sql .= $this->parseSQL($this->_groupBy, ' ');
            $this->_groupBy = [];
        }

        if (!empty($this->_having)) {
            $this->_sql .= $this->parseSQL($this->_having, ' ');
            $this->_having = [];
        }

        if (!empty($this->_orderBy)) {
            $this->_sql .= $this->parseSQL($this->_orderBy);
            $this->_orderBy = [];
        }

        if (!empty($this->_limit)) {
            $this->_sql .= $this->parseSQL($this->_limit);
            $this->_limit = [];
        }

        return $this;
    }

    /**
     * Transform array element to SQL parts.
     * @param $element
     * @param null $glue
     * @return string
     */
    public function parseSQL($element, $glue = null)
    {
        if (!is_null($glue)) {
            return implode($glue, $element);
        }

        return implode($element);
    }

    /**
     * Prepare query.
     * @param $query
     * @return mysqli_stmt
     */
    public function prepareQuery($query)
    {
        return $this->_conn->prepare($query);
    }

    /**
     * Execute query.
     * @param $command
     * @param null $dataType
     * @param array $values
     * @return bool|int|mysqli_result
     */
    public function execQuery($command, $dataType = null, $values = [])
    {
        $stmt = $this->prepareQuery($this->_sql);
        if (!empty($dataType) && !empty($values)) {
            $stmt->bind_param($dataType, ...$values);
        }
        $exec = $stmt->execute();

        switch ($command) {
            case 'crud':
                return $exec;
            case 'getResult':
                return $stmt->get_result();
            case 'numRows':
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
