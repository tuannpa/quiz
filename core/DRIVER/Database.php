<?php
/**
 * @author: Tuan Nguyen
 */

abstract class Database
{
    private $_conn;
    private $_sql = '';

    protected function __construct($serverName, $username, $password, $dbName)
    {
        if (!$this->_conn) {
            $this->_conn = new mysqli($serverName, $username, $password, $dbName);
            $this->mQuery("SET NAMES 'utf8'");

            if ($this->_conn->connect_error) {
                die('Connection failed: ' . $this->_conn->connect_error);
            }
        }
    }

    public function mQuery($sql)
    {
        return $this->_conn->query($sql);
    }

    public function mRealEscapeString($str)
    {
        return $this->_conn->real_escape_string($str);
    }

    public function mFetchAssocOne($sql)
    {
        return $this->mQuery($sql)->fetch_assoc();
    }

    public function mFetchAssoc($sql)
    {
        if ($results = $this->mQuery($sql)) {
            while ($row = $results->fetch_assoc()) {
                $arr[] = $row;
            }
        }
        $results->free();
        return $arr;
    }

    public function mNumRows($sql)
    {
        $results = $this->mQuery($sql);
        return $results->num_rows;
    }

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

    public function update($table, $field = [])
    {
        $columnValue = '';
        foreach ($field as $key => $value) {
            $columnValue .= $key . " = '" . $this->mRealEscapeString($value) . "',";
        }
        $this->_sql = 'UPDATE ' . $table . ' SET ' . trim($columnValue, ',');
        return $this;
    }

    public function delete()
    {
        $this->_sql = 'DELETE';
        return $this;
    }

    public function select()
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

    public function from($table)
    {
        $tbl = '';
        if (is_array($table)) {
            foreach ($table as $value) {
                $tbl .= ',' . $value;
            }
        }
        $this->_sql .= (!empty($tbl)) ? ' FROM ' . ltrim($tbl, ',') : ' FROM ' . $table;

        return $this;
    }

    public function where($condition = [])
    {
        $where = '';
        $i = 1;
        foreach ($condition as $key => $value) {
            if (!is_numeric($value)) {
                $value = "'" . $value . "'";
            }
            $and = ($i > 1) ? ' AND ' : ' ';
            $where .= $and . $key . ' ' . $value;
            $i++;
        }
        $this->_sql .= ' WHERE ' . $where;
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

    public function method($getDataMethod)
    {
        switch ($getDataMethod) {
            case 'crud':
                return $this->mQuery($this->_sql);
                break;

            case 'one':
                return $this->mFetchAssocOne($this->_sql);
                break;

            case 'many':
                return $this->mFetchAssoc($this->_sql);
                break;

            case 'numRows':
                return $this->mNumRows($this->_sql);
                break;
        }
    }

    public function __destruct()
    {
        if ($this->_conn) {
            $this->_conn->close();
        }
    }
}



