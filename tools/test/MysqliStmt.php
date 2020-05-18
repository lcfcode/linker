<?php

/**
 * @author LCF
 * @date 2019/3/13 14:25
 * @version 2.0.4
 */

namespace db;
class MysqliStmt
{
    private $_connect = null;
    private $_keys = '';
    private $_values = '';
    private $_bindType = '';
    private $_wheres = '';
    private $_orWheres = '';
    private $_bindValue = [];
    private $_selfErrorNo = 6000;
    private $_sql = '';
    private $_sqlParameter;

    private static $instances = [];

    public static function getInstance($config = [])
    {
        if (empty($config)) {
            $config = ['host' => '127.0.0.1', 'user' => 'liangcf', 'password' => '123456', 'database' => 'woh', 'port' => '3306', 'charset' => 'utf8'];
        }
        $ojbKey = $config['host'] . ':' . $config['user'] . ':' . $config['database'];
        if (isset(self::$instances[$ojbKey])) {
            return self::$instances[$ojbKey];
        }
        self::$instances[$ojbKey] = new self($config);
        return self::$instances[$ojbKey];
    }

    public function __construct($config)
    {
        $conn = new \mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
        $conn->set_charset($config['charset']);
        $this->_connect = $conn;
    }

    /**
     * @return \mysqli|null
     * @author LCF
     * @date 2019/8/17 18:37
     * 返回原始mysql链接
     */
    public function db()
    {
        return $this->_connect;
    }

    /**
     * @return string
     * @author LCF
     * @date 2019/9/24 10:06
     * 客户端信息
     */
    public function client_info()
    {
        return $this->_connect->get_client_info();
    }

    /**
     * @return string
     * @author LCF
     * @date 2019/9/24 10:06
     * 服务端信息
     */
    public function server_info()
    {
        return $this->_connect->server_info;
    }

    /**
     * @param bool $flag
     * @return array|mixed|string
     * @author LCF
     * @date 2019/8/17 18:37
     * 返回最后一条执行的sql语句
     */
    public function getLastSql($flag = true)
    {
        if (is_array($this->_sqlParameter)) {
            $parameter = $this->_sqlParameter;
            $explodeArr = explode('?', $this->_sql);
            $index = substr_count($this->_sql, '?');

            for ($i = 0; $i < $index; $i++) {
                $param = $parameter[$i + 1];
                $explodeArr[$i] = $explodeArr[$i] . "'{$param}'";
            }
            $sql = implode('', $explodeArr);

//            $parameter = $this->_sqlParameter;
//            $explodeArr = explode('?', $this->_sql);
//            $index = substr_count($this->_sql, '?');
//            $i = 1;
//            $tmp = [];
//            foreach ($explodeArr as $item) {
//                if (trim($item)) {
//                    if ($i <= $index) {
//                        $tmp[] = $item . "'{$this->_sqlParameter[$i]}'";
//                    } else {
//                        $tmp[] = $item;
//                    }
//                    $i++;
//                }
//            }
//            $sql = implode('', $tmp);
        } else {
            $sql = str_replace('?', "'{$this->_sqlParameter}'", $this->_sql);
            $parameter = [$this->_sqlParameter];
        }
        if ($flag === true) {
            return $sql;
        }
        return ['sql' => $this->_sql, 'parameter' => $parameter];
    }

    public function insert($table, $data)
    {
        $sql = 'insert into ' . $table;
        $this->clear();
        $this->iBand($data);
        $sql .= ' (' . $this->_keys . ') values (' . $this->_values . ')';
        $args[] = $this->_bindType;
        $parameter = array_merge($args, $this->_bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        if ($stmt->errno) {
            throw new \Exception('MysqliClass::insert exception , message : ' . $stmt->error, $stmt->errno);
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        if ($affectedRows > 0) {
            return $affectedRows;
        }
        return false;
    }

    public function updateId($table, $idField, $id, $data)
    {
        $sql = 'update ' . $table . ' set ';
        $this->clear();
        $this->uBand($data);
        $sql .= ' ' . $this->_keys . ' where ' . $idField . '=? ';
        $this->_bindType .= $this->_determineType($id);
        $args[] = $this->_bindType;
        array_push($this->_bindValue, $id);
        $parameter = array_merge($args, $this->_bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        if ($stmt->errno) {
            throw new \Exception('MysqliClass::updateId exception , message : ' . $stmt->error, $stmt->errno);
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        if ($affectedRows > 0) {
            return $affectedRows;
        }
        return false;
    }

    public function deleteId($table, $idField, $id)
    {
        $sql = 'delete from ' . $table . ' where ' . $idField . '=?';
        $bindType = $this->_determineType($id);
        $stmt = $this->_prepare($sql, $id);
        $stmt->bind_param($bindType, $id);
        $stmt->execute();
        if ($stmt->errno) {
            throw new \Exception('MysqliClass::deleteId exception , message : ' . $stmt->error, $stmt->errno);
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->free_result();
        $stmt->close();
        if ($affectedRows > 0) {
            return $affectedRows;
        }
        return false;
    }

    public function update($table, $data, $where)
    {
        $sql = 'update ' . $table . ' set ';
        $this->clear();
        $this->uBand($data);
        $sql .= ' ' . $this->_keys . ' where ';
        $this->_and($where);
        $args[] = $this->_bindType;
        $sql .= ' ' . $this->_wheres;
        $parameter = array_merge($args, $this->_bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        if ($stmt->errno) {
            throw new \Exception('MysqliClass::update exception , message : ' . $stmt->error, $stmt->errno);
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        if ($affectedRows > 0) {
            return $affectedRows;
        }
        return false;
    }

    public function delete($table, $where)
    {
        $sql = 'delete from ' . $table;
        $this->clear();
        $this->_and($where);
        $sql .= ' where ' . $this->_wheres;
        $args[] = $this->_bindType;
        $parameter = array_merge($args, $this->_bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        if ($stmt->errno) {
            throw new \Exception('MysqliClass::delete exception , message : ' . $stmt->error, $stmt->errno);
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->free_result();
        $stmt->close();
        if ($affectedRows > 0) {
            return $affectedRows;
        }
        return false;
    }

    public function selectId($table, $idField, $id, $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $idField . '=? limit 1';
        $bindType = $this->_determineType($id);
        $stmt = $this->_prepare($sql, $id);
        $stmt->bind_param($bindType, $id);
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        if ($returnData) {
            return $returnData[0];
        }
        return [];
    }

    /**
     * @param $table
     * @param $where
     * @param array $order
     * @param int $offset
     * @param int $fetchNum
     * @param array $getInfo
     * @return array
     * @author LCF
     * @date 2019/8/17 21:34
     * 条件查询
     */
    public function select($table, $where, $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table;
        $this->clear();
        $this->_and($where);
        $sql .= ' where ' . $this->_wheres;
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        $args[] = $this->_bindType;
        $parameter = array_merge($args, $this->_bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    /**
     * @param $table
     * @param $where
     * @param array $order
     * @param array $getInfo
     * @return array|mixed
     * @author LCF
     * @date 2019/8/17 21:32
     * 查询单条数据，一般用于登录类型的
     */
    public function selectOne($table, $where, $order = [], $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table;
        $this->clear();
        $this->_and($where);
        $sql .= ' where ' . $this->_wheres;
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        $sql .= ' limit 1';
        $args[] = $this->_bindType;
        $parameter = array_merge($args, $this->_bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        if ($returnData) {
            return $returnData[0];
        }
        return [];
    }

    /**
     * @param $table
     * @param array $order
     * @param int $offset
     * @param int $fetchNum
     * @param array $getInfo
     * @return array
     * @author LCF
     * @date 2019/8/17 21:33
     * 查询所有数据
     */
    public function selectAll($table, $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table;
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        $stmt = $this->_prepare($sql);
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        return $returnData;
    }

    /**
     * @param $table
     * @param array $where
     * @param array $order
     * @param int $offset
     * @param int $fetchNum
     * @param array $getInfo
     * @return array
     * @author LCF
     * @date 2019/8/17 21:33
     * selectAll 方法 和 select 方法的合体
     */
    public function selects($table, $where = [], $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'])
    {
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table;
        if (!empty($where)) {
            $this->clear();
            $this->_and($where);
            $sql .= ' where ' . $this->_wheres;
        }
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        if (empty($this->_bindValue)) {
            $stmt = $this->_prepare($sql);
        } else {
            $args[] = $this->_bindType;
            $parameter = array_merge($args, $this->_bindValue);
            $stmt = $this->_prepare($sql, $parameter);
            call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        }
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    /**
     * @param $sql
     * @param array $param
     * @return array|bool|int
     * @author LCF
     * @date 2019/8/17 21:35
     * 执行sql语句,此处参数绑定注意参数顺序
     */
    public function query($sql, $param = [])
    {
        if (!empty($param)) {
            $paramTmp = [];
            $bindType = '';
            foreach ($param as $key => $value) {
                $bindType .= $this->_determineType($param[$key]);
                $paramTmp[] = $param[$key];
            }
            $parameter = array_merge([$bindType], $paramTmp);
            $stmt = $this->_prepare($sql, $parameter);
            call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        } else {
            $stmt = $this->_prepare($sql);
        }
        $stmt->execute();
        if (stripos(trim($sql), 'select') === 0) {
            $returnData = $this->_dynamicBindResults($stmt);
        } else {
            $res = $stmt->affected_rows;
            if ($res > 0) {
                $returnData = $res;
            } else {
                $returnData = false;
            }
        }
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    /**
     * @param $table
     * @param $stringName
     * @param $content
     * @param array $where
     * @param array $order
     * @param int $offset
     * @param int $fetchNum
     * @param array $getInfo
     * @return array
     * @author LCF
     * @date 2019/8/17 21:36
     * like语句
     */
    public function like($table, $stringName, $content, $where = [], $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'])
    {
        $content = addslashes($content);
        if (stristr($content, '_')) {
            $content = str_replace('_', "\\_", $content);
        }
        if (stristr($content, '%')) {
            $content = str_replace('%', '', $content);
        }
        $content = $this->_connect->real_escape_string($content);
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $stringName . " like '%" . $content . "%' ";
        if (!empty($where)) {
            $this->clear();
            $this->_and($where);
            $sql .= ' and ' . $this->_wheres;
        }
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        if (empty($this->_bindValue)) {
            $stmt = $this->_prepare($sql);
        } else {
            $args[] = $this->_bindType;
            $parameter = array_merge($args, $this->_bindValue);
            $stmt = $this->_prepare($sql, $parameter);
            call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        }
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    public function rlike($table, $stringName, $content, $where = [], $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'])
    {
        $content = addslashes($content);
        if (stristr($content, '_')) {
            $content = str_replace('_', "\\_", $content);
        }
        if (stristr($content, '%')) {
            $content = str_replace('%', '', $content);
        }
        $content = $this->_connect->real_escape_string($content);
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $stringName . " like '%" . $content . "' ";
        if (!empty($where)) {
            $this->clear();
            $this->_and($where);
            $sql .= ' and ' . $this->_wheres;
        }
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        if (empty($this->_bindValue)) {
            $stmt = $this->_prepare($sql);
        } else {
            $args[] = $this->_bindType;
            $parameter = array_merge($args, $this->_bindValue);
            $stmt = $this->_prepare($sql, $parameter);
            call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        }
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    public function llike($table, $stringName, $content, $where = [], $order = [], $offset = 0, $fetchNum = 0, $getInfo = ['*'])
    {
        $content = addslashes($content);
        if (stristr($content, '_')) {
            $content = str_replace('_', "\\_", $content);
        }
        if (stristr($content, '%')) {
            $content = str_replace('%', '', $content);
        }
        $content = $this->_connect->real_escape_string($content);
        $sql = 'select ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $stringName . " like '" . $content . "%' ";
        if (!empty($where)) {
            $this->clear();
            $this->_and($where);
            $sql .= ' and ' . $this->_wheres;
        }
        if (!empty($order)) {
            $sql .= ' order by ' . $this->_order($order);
        }
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' limit ' . $offset . ',' . $fetchNum;
        }
        if (empty($this->_bindValue)) {
            $stmt = $this->_prepare($sql);
        } else {
            $args[] = $this->_bindType;
            $parameter = array_merge($args, $this->_bindValue);
            $stmt = $this->_prepare($sql, $parameter);
            call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        }
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    /**
     * @param $table
     * @param $multiInsertData
     * @param array $keys
     * @return bool|int
     * @author LCF
     * @date 2019/8/17 21:36
     * 多条语句执行插入方法2  建议使用
     */
    public function insertMultiple($table, $multiInsertData, $keys = [])
    {
        $sql = 'insert into ' . $table;
        $keyArr = [];
        $valueArr = [];
        $bindType = '';
        $sqlTemp = '';
        $index = 0;
        foreach ($multiInsertData as $data) {
            if (empty($data)) {
                continue;
            }
            $tmpArr = [];
            foreach ($data as $key => $value) {
                if ($index == 0) {
                    $keyArr[] = $key;
                }
                $tmpArr[] = '?';
                $valueArr[] =& $data[$key];
                $bindType .= $this->_determineType($value);
            }
            $values = implode(',', $tmpArr);
            $sqlTemp .= '(' . $values . '),';
            $index++;
        }
        $sqlTemp = rtrim($sqlTemp, ',');
        if (empty($keys)) {
            $keys = implode(',', $keyArr);
        }
        $sql .= ' (' . $keys . ') values ' . $sqlTemp;
        $bindValue = $valueArr;
        $args[] = $bindType;
        $parameter = array_merge($args, $bindValue);
        $stmt = $this->_prepare($sql, $parameter);
        call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        if ($affectedRows > 0) {
            return $affectedRows;
        }
        return false;
    }

    public function count($table, $where = [], $columnName = '*', $distinct = false)
    {
        if ($distinct) {
            $sql = "select count( distinct " . $columnName . ") as count from " . $table;
        } else {
            $sql = "select count(" . $columnName . ") as count from " . $table;
        }
        $returnData = $this->_group($sql, $where);
        return $returnData[0]['count'];
    }

    public function min($table, $columnName, $where = [])
    {
        $sql = "select min(" . $columnName . ") as min from " . $table;
        $returnData = $this->_group($sql, $where);
        return $returnData[0]['min'];
    }

    public function max($table, $columnName, $where = [])
    {
        $sql = "select max(" . $columnName . ") as max from " . $table;
        $returnData = $this->_group($sql, $where);
        return $returnData[0]['max'];
    }

    public function avg($table, $columnName, $where = [])
    {
        $sql = "select avg(" . $columnName . ") as avg from " . $table;
        $returnData = $this->_group($sql, $where);
        return $returnData[0]['avg'];
    }

    public function sum($table, $columnName, $where = [])
    {
        $sql = "select sum(" . $columnName . ") as sum from " . $table;
        $returnData = $this->_group($sql, $where);
        return $returnData[0]['sum'];
    }

    private function _group($sql, $where = [])
    {
        if (!empty($where)) {
            $this->clear();
            $this->_and($where);
            $sql .= ' where ' . $this->_wheres;
        }
        if (empty($this->_bindValue)) {
            $stmt = $this->_prepare($sql);
        } else {
            $args[] = $this->_bindType;
            $parameter = array_merge($args, $this->_bindValue);
            $stmt = $this->_prepare($sql, $parameter);
            call_user_func_array([$stmt, 'bind_param'], self::refValues($parameter));
        }
        $stmt->execute();
        $returnData = $this->_dynamicBindResults($stmt);
        $stmt->free_result();
        $stmt->close();
        $this->clear();
        return $returnData;
    }

    public function beginTransaction()
    {
        return $this->_connect->autocommit(false);
    }

    public function commitTransaction()
    {
        return $this->_connect->commit();
    }

    public function rollbackTransaction()
    {
        return $this->_connect->rollback();
    }

    private function clear()
    {
        $this->_keys = '';
        $this->_values = '';
        $this->_bindType = '';
        $this->_wheres = '';
        $this->_orWheres = '';
        $this->_bindValue = [];
    }

    private function iBand($data)
    {
        $keyArr = [];
        $tmpArr = [];
        $valueArr = [];
        foreach ($data as $key => $value) {
            $keyArr[] = $key;
            $tmpArr[] = '?';
            $valueArr[] =& $data[$key];
            $this->_bindType .= $this->_determineType($value);
        }
        $this->_keys = implode(',', $keyArr);
        $this->_values = implode(',', $tmpArr);
        $this->_bindValue = $valueArr;
        return true;
    }

    private function _determineType($dataType)
    {
        switch (gettype($dataType)) {
            case 'NULL':
            case 'string':
                return 's';
                break;
            case 'boolean':
            case 'integer':
                return 'i';
                break;
            case 'blob':
                return 'b';
                break;
            case 'double':
                return 'd';
                break;
        }
        throw new \Exception('MysqliClass::_determineType exception , message : data type exception!', $this->_selfErrorNo);
    }

    /**
     * @param $sql
     * @param null $parameter
     * @return \mysqli_stmt
     * @author LCF
     * @date 2019/9/24 9:57
     */
    private function _prepare($sql, $parameter = null)
    {
        $this->_sql = $sql;
        $this->_sqlParameter = $parameter;
        $stmt = $this->_connect->prepare($sql);
        if (!$stmt) {
            $msg = $this->_connect->error . " --SQL: " . $this->sqlHtml($this->getLastSql());
            throw new \Exception('MysqliClass::_prepare exception , message : ' . $msg, $this->_selfErrorNo);
        }
        return $stmt;
    }

    private static function refValues($data)
    {
        $refs = [];
        foreach ($data as $key => $value) {
            $refs[] =& $data[$key];
        }
        return $refs;
    }

    private function uBand($data)
    {
        $keyArr = [];
        $valueArr = [];
        foreach ($data as $key => $value) {
            $keyArr[] = $key . '=? ';
            $valueArr[] =& $data[$key];
            $this->_bindType .= $this->_determineType($value);
        }
        $this->_keys = implode(',', $keyArr);
        $this->_bindValue = $valueArr;
        return true;
    }

    private function _and($where)
    {
        $whereValueArr = [];
        $strTmp = '';
        foreach ($where as $keys => $values) {
            if (!strpos($keys, '::')) {
                $strTmp .= ' and ' . $keys . '=? ';
            } else {
                $strTmp .= ' and ' . str_replace('::', ' ', $keys) . ' ? ';
            }
            $whereValueArr[] =& $where[$keys];
            $this->_bindType .= $this->_determineType($values);
        }
        $this->_wheres = substr($strTmp, 4);
        if (!empty($this->_bindValue)) {
            $this->_bindValue = array_merge($this->_bindValue, $whereValueArr);
        } else {
            $this->_bindValue = $whereValueArr;
        }
        return true;
    }

    private function _dynamicBindResults($stmt)
    {
        $result = $stmt->get_result();
        $results = [];
        while ($resultRow = $result->fetch_assoc()) {
            $results[] = $resultRow;
        }
        return $results;
    }

    private function _order($order)
    {
        $orderArr = [];
        foreach ($order as $orderKey => $rowOrder) {
            $orderArr[] = $orderKey . ' ' . $rowOrder;
        }
        return implode(',', $orderArr);
    }

    private function _dynamicBindResults2($stmt)
    {
        $parameters = [];
        $results = [];
        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $parameters[] = &$row[$field->name];
        }
        call_user_func_array([$stmt, 'bind_result'], $parameters);
        while ($stmt->fetch()) {
            $x = [];
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            $results[] = $x;
        }
        return $results;
    }

    private function sqlHtml($sql)
    {
        return '<b>' . $sql . '</b>';
    }

    public function close()
    {
        if ($this->_connect) {
            $this->_connect->close();
        }
    }

    function __destruct()
    {
        if ($this->_connect) {
            $this->_connect->close();
        }
    }
}