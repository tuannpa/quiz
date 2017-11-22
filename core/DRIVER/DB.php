<?php

abstract class DB
{
    private $_conn;
    private $_server_name = 'localhost';
    private $_username = 'root';
    private $_password = '';
    private $_db_name = 'db_quiz';
    private $_sql = '';

    protected function __construct()
    {
        if (!$this->_conn) {
            $this->_conn = new mysqli($this->_server_name, $this->_username, $this->_password, $this->_db_name);
            $this->mQuery("SET NAMES 'utf8'");

            if ($this->_conn->connect_error)
                die('Connection failed: ' . $this->_conn->connect_error);
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

    public function findById($table, $id, $field = ['*'])
    {
        $colName = '';
        foreach ($field as $value) {
            $colName .= ',' . $value;
        }
        $sql = 'SELECT ' . ltrim($colName, ',') . ' FROM ' . $table . ' WHERE id = ' . $id;
        return $this->mFetchAssocOne($sql);
    }

    public function all($table, $field = ['*'], $extended = '')
    {
        $colName = '';
        foreach ($field as $value) {
            $colName .= ',' . $value;
        }
        $sql = 'SELECT ' . ltrim($colName, ',') . ' FROM ' . $table . ' ' . $extended;
        return $this->mFetchAssoc($sql);
    }

    public function select($field = ['*'])
    {
        $colName = '';
        foreach ($field as $value) {
            $colName .= ',' . $value;
        }
        $this->_sql = 'SELECT ' . ltrim($colName, ',');
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




