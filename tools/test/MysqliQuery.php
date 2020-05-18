<?php

namespace db;
class MysqliQuery
{
    /**
     * @var \mysqli
     */
    private $link;
    private static $instance = null;
    private $re_sql;
    private static $dbName = [];

    private function __construct($config)
    {
        $conn = new \mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
        if ($conn->connect_errno) {
            throw new \Exception('database link failed  --error:' . $conn->error . ' ; connect_errno:' . $conn->connect_errno);
        }
        $conn->set_charset($config['charset']);
        self::$dbName[$config['database']] = $config['database'];
        $this->link = $conn;
    }

    private function __clone()
    {
    }

    public static function getInstance($config = [])
    {
        if (empty($config)) {
            $config = array('host' => '127.0.0.1', 'user' => 'liangcf', 'password' => '123456', 'database' => 'woh', 'port' => '3306', 'charset' => 'utf8');
        }
        if (self::$instance && isset(self::$dbName[$config['database']])) {
            return self::$instance;
        }
        self::$instance = new self($config);
        return self::$instance;
    }

    public function mysqli()
    {
        return $this->link;
    }

    public function getLastSql()
    {
        return $this->re_sql;
    }

    function __destruct()
    {
        if ($this->link) {
            $this->link->close();
            $this->link = null;
        }
    }

    public function insert($table, $data)
    {
        $keyArr = [];
        $valueArr = [];
        foreach ($data as $key => $value) {
            $keyArr[] = "`" . $key . "`";
            $valueArr[] = "'" . $value . "'";
        }
        $keys = implode(',', $keyArr);
        $values = implode(',', $valueArr);
        $sql = "insert into " . $table . " (" . $keys . ") values (" . $values . ")";
        $this->link->query($sql);
        $this->re_sql = $sql;
        $res = $this->link->affected_rows;
        if ($res > 0) {
            return $res;
        }
        return false;
    }

    public function updateId($table, $id, $data)
    {
        $tmpArr = [];
        foreach ($data as $key => $value) {
            $tmpArr[] = "`" . $key . "`='" . $value . "'";
        }
        $keyAndValues = implode(",", $tmpArr);
        $sql = 'update ' . $table . ' set ' . $keyAndValues . " where id='{$id}'";
        $this->link->query($sql);
        $this->re_sql = $sql;
        $res = $this->link->affected_rows;
        if ($res > 0) {
            return $res;
        }
        return false;
    }

    public function update($table, $data, $where)
    {
        $tmpArr = [];
        foreach ($data as $key => $value) {
            $tmpArr[] = $key . "='" . $value . "'";
        }
        $keyAndValues = implode(',', $tmpArr);
        $sql = 'update ' . $table . ' set ' . $keyAndValues . ' where ';
        $whereString = $this->andWhere($where);
        $sql .= $whereString;
        $this->link->query($sql);
        $this->re_sql = $sql;
        $res = $this->link->affected_rows;
        if ($res > 0) {
            return $res;
        }
        return false;
    }

    public function deleteId($table, $id)
    {
        $sql = 'delete from ' . $table . " where id='{$id}'";
        $this->link->query($sql);
        $this->re_sql = $sql;
        $res = $this->link->affected_rows;
        if ($res > 0) {
            return $res;
        }
        return false;
    }

    public function delete($table, $where)
    {
        $sql = "delete from " . $table . " where ";
        $whereString = $this->andWhere($where);
        $sql .= $whereString;
        $this->link->query($sql);
        $this->re_sql = $sql;
        $res = $this->link->affected_rows;
        if ($res > 0) {
            return $res;
        }
        return false;
    }

    public function select($table, $where, $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'], $orWhere = [])
    {
        $whereString = $this->andWhere($where);
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $whereString;
        if (!empty($orWhere)) {
            $orWhereString = $this->orWhere($orWhere);
            $sql .= ' or ' . $orWhereString;
        }
        if (!empty($order)) {
            $orderTmpArr = [];
            foreach ($order as $orderKey => $rowOrder) {
                $orderTmpArr[] = $orderKey . ' ' . $rowOrder;
            }
            $sql .= ' order by ' . implode(',', $orderTmpArr);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = [];
        if ($result) {
            while ($resultRow = $result->fetch_assoc()) {
                $returnData[] = $resultRow;
            }
        }
        $result->close();
        return $returnData;
    }

    public function selectAll($table, $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'], $orWhere = [])
    {
        $sql = "select " . implode(',', $getInfo) . ' from ' . $table;
        if (!empty($orWhere)) {
            $orWhereString = $this->orWhere($orWhere);
            $sql .= ' where ' . $orWhereString;
        }
        if (!empty($order)) {
            $orderTmpArr = [];
            foreach ($order as $orderKey => $rowOrder) {
                $orderTmpArr[] = $orderKey . ' ' . $rowOrder;
            }
            $sql .= ' order by ' . implode(',', $orderTmpArr);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = [];
        if ($result) {
            while ($resultRow = $result->fetch_assoc()) {
                $returnData[] = $resultRow;
            }
        }
        return $returnData;
    }

    public function selectId($table, $id, $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . " where id='{$id}'";
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = $result->fetch_assoc();
        $result->close();
        return $returnData;
    }

    public function query($sql)
    {
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        if (stristr($sql, 'select')) {
            $returnData = [];
            if ($result) {
                while ($resultRow = $result->fetch_assoc()) {
                    $returnData[] = $resultRow;
                }
            }
            $result->close();
            return $returnData;
        } else {
            $res = $this->link->affected_rows;
            if ($res > 0) {
                return $res;
            }
            return false;
        }
    }

    public function notEqualAll($table, $whereString, $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $whereString;
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = [];
        if ($result) {
            while ($resultRow = $result->fetch_assoc()) {
                $returnData[] = $resultRow;
            }
        }
        $result->close();
        return $returnData;
    }

    public function selects($table, $where = [], $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'], $orWhere = [])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table;
        if (!empty($where)) {
            $whereString = $this->andWhere($where);
            $sql .= ' where ' . $whereString;
        }
        if (!empty($orWhere)) {
            $orWhereString = $this->orWhere($orWhere);
            if (empty($where)) {
                $sql .= ' where ' . $orWhereString;
            } else {
                $sql .= ' or ' . $orWhereString;
            }
        }
        if (!empty($order)) {
            $orderArr = [];
            foreach ($order as $orderKey => $rowOrder) {
                $orderArr[] = $orderKey . ' ' . $rowOrder;
            }
            $sql .= ' order by ' . implode(',', $orderArr);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = [];
        if ($result) {
            while ($resultRow = $result->fetch_assoc()) {
                $returnData[] = $resultRow;
            }
        }
        $result->close();
        return $returnData;
    }

    public function like($table, $stringName, $content, $where = [], $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'], $orWhere = [])
    {
        if (stristr($content, '_')) {
            $content = str_replace('_', "\\_", $content);
        }
        if (stristr($content, '%')) {
            $content = str_replace('%', '', $content);
        }
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $stringName . " like '%" . $content . "%' ";
        if (!empty($where)) {
            $whereString = $this->andWhere($where);
            $sql .= ' and ' . $whereString;
        }
        if (!empty($orWhere)) {
            $orWhereString = $this->orWhere($orWhere);
            $sql .= ' or ' . $orWhereString;
        }
        if (!empty($order)) {
            $orderArr = [];
            foreach ($order as $orderKey => $rowOrder) {
                $orderArr[] = $orderKey . ' ' . $rowOrder;
            }
            $sql .= ' order by ' . implode(',', $orderArr);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = [];
        if ($result) {
            while ($resultRow = $result->fetch_assoc()) {
                $returnData[] = $resultRow;
            }
        }
        $result->close();
        return $returnData;
    }

    public function count($table, $where = [], $columnName = '*', $orWhere = [])
    {
        $sql = "select count(" . $columnName . ") as count from " . $table;
        $returnData = $this->_group($sql, $where, $orWhere);
        return $returnData['count'];
    }

    public function min($table, $columnName, $where = [], $orWhere = [])
    {
        if (empty($columnName)) {
            throw new \Exception('MysqliQuery::min function parameter error!', 1003);
        }
        $sql = "select min(" . $columnName . ") as min from " . $table;
        $returnData = $this->_group($sql, $where, $orWhere);
        return $returnData['min'];
    }

    public function max($table, $columnName, $where = [], $orWhere = [])
    {
        if (empty($columnName)) {
            throw new \Exception('MysqliQuery::max function parameter error!', 1003);
        }
        $sql = "select max(" . $columnName . ") as max from " . $table;
        $returnData = $this->_group($sql, $where, $orWhere);
        return $returnData['max'];
    }

    public function avg($table, $columnName, $where = [], $orWhere = [])
    {
        if (empty($columnName)) {
            throw new \Exception('MysqliQuery::avg function parameter error!', 1003);
        }
        $sql = "select avg(" . $columnName . ") as avg from " . $table;
        $returnData = $this->_group($sql, $where, $orWhere);
        return $returnData['avg'];
    }

    public function sum($table, $columnName, $where = [], $orWhere = [])
    {
        if (empty($columnName)) {
            throw new \Exception('MysqliQuery::sum function parameter error!', 1003);
        }
        $sql = "select sum(" . $columnName . ") as sum from " . $table;
        $returnData = $this->_group($sql, $where, $orWhere);
        return $returnData['sum'];
    }

    private function _group($sql, $where, $orWhere = [])
    {
        if (!empty($where) && is_array($where)) {
            $whereString = $this->andWhere($where);
            $sql .= " where " . $whereString;
        }
        if (!empty($orWhere) && is_array($orWhere)) {
            $orWhereString = $this->orWhere($orWhere);
            if (empty($where)) {
                $sql .= ' where ' . $orWhereString;
            } else {
                $sql .= ' or ' . $orWhereString;
            }
        }
        $result = $this->link->query($sql);
        $this->re_sql = $sql;
        $returnData = $result->fetch_assoc();
        $result->close();
        return $returnData;
    }

    protected function andWhere($where)
    {
        $tmpArr = [];
        foreach ($where as $whereKey => $whereRow) {
            $tmpArr[] = $whereKey . "= '" . $whereRow . "'";
        }
        $whereStringTmp = '';
        foreach ($tmpArr as $row) {
            $whereStringTmp .= $row . ' and ';
        }
        $whereString = substr($whereStringTmp, 0, -4);
        return $whereString;
    }

    protected function orWhere($orWhere)
    {
        $orWhereTmp = [];
        foreach ($orWhere as $orWhereKeys => $orWhereValues) {
            $orWhereTmp[] = $orWhereKeys . " = '" . $orWhereValues . "'";
        }
        $orWhereString = '';
        foreach ($orWhereTmp as $orRow) {
            $orWhereString .= ' or ' . $orRow;
        }
        return $orWhereString;
    }
}