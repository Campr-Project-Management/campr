<?php
/*
    @author dhtmlx.com
    @license GPL, see license.txt
*/
require_once 'db_common.php';
/*! Implementation of DataWrapper for PDO

if you plan to use it for Oracle - use Oracle connection type instead
**/
class PDODBDataWrapper extends DBDataWrapper
{
    private $last_result; //!< store result or last operation

    public function query($sql)
    {
        LogMaster::log($sql);

        $res = $this->connection->query($sql);
        if ($res === false) {
            $message = $this->connection->errorInfo();
            throw new Exception("PDO - sql execution failed\n".$message[2]);
        }

        return new PDOResultSet($res);
    }

    protected function select_query($select, $from, $where, $sort, $start, $count)
    {
        if (!$from) {
            return $select;
        }

        $sql = 'SELECT '.$select.' FROM '.$from;
        if ($where) {
            $sql .= ' WHERE '.$where;
        }
        if ($sort) {
            $sql .= ' ORDER BY '.$sort;
        }
        if ($start || $count) {
            if ($this->connection->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql') {
                $sql .= ' OFFSET '.$start.' LIMIT '.$count;
            } else {
                $sql .= ' LIMIT '.$start.','.$count;
            }
        }

        return $sql;
    }

    public function tables_list()
    {
        $result = $this->query('SHOW TABLES');
        if ($result === false) {
            throw new Exception("MySQL operation failed\n".mysql_error($this->connection));
        }

        $tables = array();
        while ($table = $result->next()) {
            $tables[] = $table[0];
        }

        return $tables;
    }

    public function fields_list($table)
    {
        $result = $this->query('SHOW COLUMNS FROM `'.$table.'`');
        if ($result === false) {
            throw new Exception("MySQL operation failed\n".mysql_error($this->connection));
        }

        $fields = array();
        $id = '';
        while ($field = $result->next()) {
            if ($field['Key'] == 'PRI') {
                $id = $field['Field'];
            } else {
                $fields[] = $field['Field'];
            }
        }

        return array('fields' => $fields, 'key' => $id);
    }

    public function get_next($res)
    {
        $data = $res->next();

        return $data;
    }

    public function get_new_id()
    {
        return $this->connection->lastInsertId();
    }

    public function escape($str)
    {
        $res = $this->connection->quote($str);
        if ($res === false) { //not supported by pdo driver
            return str_replace("'", "''", $str);
        }

        return substr($res, 1, -1);
    }
}

class PDOResultSet
{
    private $res;
    public function __construct($res)
    {
        $this->res = $res;
    }
    public function next()
    {
        $data = $this->res->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            $this->res->closeCursor();

            return null;
        }

        return $data;
    }
}
