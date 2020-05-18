<?php

/**
 * @author LCF
 * @date 2019/3/13 14:25
 * @version 1.0
 * SQL Server 驱动
 */

namespace db;
/**
 * Class MssqlClass
 * @package scanlib\utils
 * 原本设计保留单例，现在也满足单例情况，需要自行改写
 */
class MssqlClass
{
    /**
     * @var false|null|resource
     */
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
        $connectionOptions = array("Database" => $config['database'], "Uid" => $config['user'], "PWD" => $config['password'], 'CharacterSet' => $config['charset']);
        $this->_connect = sqlsrv_connect($config['host'] . ',' . $config['port'], $connectionOptions);
        //todo
//        sqlsrv_configure("WarningsReturnAsErrors", 0);
//        sqlsrv_configure("WarningsReturnAsErrors", 1);
    }

    /**
     * @return false|resource
     * @author LCF
     * @date 2019/8/17 18:37
     * 返回原始sqlsrv_connect链接
     */
    public function db()
    {
        return $this->_connect;
    }

    /**
     * @return array|null
     * @author LCF
     * @date 2019/9/24 10:06
     * 客户端信息
     */
    public function client_info()
    {
        return sqlsrv_client_info($this->_connect);
    }

    /**
     * @return array
     * @author LCF
     * @date 2019/9/24 10:06
     * 服务端信息
     */
    public function server_info()
    {
        return sqlsrv_server_info($this->_connect);
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
                $param = $parameter[$i];
                $explodeArr[$i] = $explodeArr[$i] . "N'{$param}'";
            }
            $sql = implode('', $explodeArr);
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
//        $sql .= ' (' . $this->_keys . ') values (' . $this->_values . ');SELECT SCOPE_IDENTITY() AS id from '.$table;
//        $stmt = sqlsrv_query($this->_connect, $sql, $this->_bindValue);

        $column = $this->selfselect($sql, $this->_bindValue, true);
        $this->clear();
        return $column;
    }

    public function updateId($table, $idField, $id, $data)
    {
        $sql = 'update ' . $table . ' set ';
        $this->clear();
        $this->uBand($data);
        $sql .= ' ' . $this->_keys . ' where ' . $idField . '=? ';
        array_push($this->_bindValue, $id);
        $column = $this->selfselect($sql, $this->_bindValue, true);
        $this->clear();
        return $column;
    }

    public function deleteId($table, $idField, $id)
    {
        $this->clear();
        $sql = 'delete from ' . $table . ' where ' . $idField . '=?';
        $column = $this->selfselect($sql, [$id], true);
        $this->clear();
        return $column;
    }

    public function update($table, $data, $where)
    {
        $sql = 'update ' . $table . ' set ';
        $this->clear();
        $this->uBand($data);
        $sql .= ' ' . $this->_keys . ' where ';
        $this->_and($where);
        $sql .= ' ' . $this->_wheres;
        $column = $this->selfselect($sql, $this->_bindValue, true);
        $this->clear();
        return $column;
    }

    public function delete($table, $where)
    {
        $sql = 'delete from ' . $table;
        $this->clear();
        $this->_and($where);
        $sql .= ' where ' . $this->_wheres;
        $column = $this->selfselect($sql, $this->_bindValue, true);
        $this->clear();
        return $column;
    }

    public function selectId($table, $idField, $id, $getInfo = ['*'])
    {
        $this->clear();
        $sql = 'select top 1 ' . implode(',', $getInfo) . ' from ' . $table . ' where ' . $idField . '=? ';
        $returnData = $this->selfselect($sql, [$id]);
        $this->clear();
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
        //SELECT*FROM(SELECT *, ROW_NUMBER() OVER (ORDER BY user_id)AS RowId FROM [MGUsers].[dbo].[user] WHERE 1=1) AS b WHERE RowId BETWEEN 0 AND 10
        //SELECT*FROM(SELECT *, ROW_NUMBER() OVER (ORDER BY rand())AS RowId FROM [MGUsers].[dbo].[user] WHERE 1=1) AS b WHERE RowId BETWEEN 0 AND 10
        $field = implode(',', $getInfo);
        $this->clear();
        $this->_and($where);
        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $innerSql = 'select ' . $field . ', row_number() over(order by ' . $thatOrder . ' ) as row_num from ' . $table . ' where ' . $this->_wheres;
        $sql = 'select ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' where row_num between ' . $offset . ' and ' . $fetchNum;
        }
        $returnData = $this->selfselect($sql, $this->_bindValue);
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
        $field = implode(',', $getInfo);
        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $this->clear();
        $this->_and($where);
        $innerSql = 'select ' . $field . ', row_number() over(order by ' . $thatOrder . ' ) as row_num from ' . $table . ' where ' . $this->_wheres;
        $sql = 'select top 1 ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        $returnData = $this->selfselect($sql, $this->_bindValue);
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
        $field = implode(',', $getInfo);
        $this->clear();
        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $innerSql = 'select ' . $field . ' , row_number() over (order by ' . $thatOrder . ' ) as row_num from ' . $table;
        $sql = 'select ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' where row_num between ' . $offset . ' and ' . $fetchNum;
        }
        $returnData = $this->selfselect($sql, $this->_bindValue);
        $this->clear();

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
        $field = implode(',', $getInfo);

        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $innerSql = 'select ' . $field . ' , row_number() over (order by ' . $thatOrder . ' ) as row_num from ' . $table;
        if ($where) {
            $this->clear();
            $this->_and($where);
            $innerSql .= ' where ' . $this->_wheres;
        }

        $sql = 'select ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' where row_num between ' . $offset . ' and ' . $fetchNum;
        }

        $returnData = $this->selfselect($sql, $this->_bindValue);
        $this->clear();
        return $returnData;
    }

    /**
     * @param $sql
     * @param array $param
     * @return array|bool|int
     * @author LCF
     * @date 2019/8/17 21:35
     * 执行sql语句,此处注意参数顺序
     */
    public function query($sql, $param = [])
    {
        $parameter = [];
        if (!empty($param)) {
            foreach ($param as $key => $value) {
                $parameter[] = $param[$key];
            }
        }
        if (stripos(trim($sql), 'select') === 0) {
            $returnData = $this->selfselect($sql, $parameter);
        } else {
            $returnData = $this->selfselect($sql, $parameter, true);
        }
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
        $field = implode(',', $getInfo);
        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $innerSql = 'select ' . $field . ' , row_number() over (order by ' . $thatOrder . ' ) as row_num from ' . $table . ' where ' . $stringName . " like N'%{$content}%' ";

        if ($where) {
            $this->clear();
            $this->_and($where);
            $innerSql .= ' and ' . $this->_wheres;
        }
        $sql = 'select ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' where row_num between ' . $offset . ' and ' . $fetchNum;
        }
        $returnData = $this->selfselect($sql, $this->_bindValue);
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
        $field = implode(',', $getInfo);
        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $innerSql = 'select ' . $field . ' , row_number() over (order by ' . $thatOrder . ' ) as row_num from ' . $table . ' where ' . $stringName . " like N'%{$content}' ";

        if ($where) {
            $this->clear();
            $this->_and($where);
            $innerSql .= ' and ' . $this->_wheres;
        }
        $sql = 'select ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' where row_num between ' . $offset . ' and ' . $fetchNum;
        }
        $returnData = $this->selfselect($sql, $this->_bindValue);
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
        $field = implode(',', $getInfo);
        if ($order) {
            $thatOrder = $this->_order($order);
        } else {
            $thatOrder = 'rand()';
        }
        $innerSql = 'select ' . $field . ' , row_number() over (order by ' . $thatOrder . ' ) as row_num from ' . $table . ' where ' . $stringName . " like N'{$content}%' ";

        if ($where) {
            $this->clear();
            $this->_and($where);
            $innerSql .= ' and ' . $this->_wheres;
        }
        $sql = 'select ' . $field . ' from( ' . $innerSql . ' ) as linker ';
        if ($fetchNum > 0 && $offset >= 0) {
            $sql .= ' where row_num between ' . $offset . ' and ' . $fetchNum;
        }
        $returnData = $this->selfselect($sql, $this->_bindValue);
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
        $parameter = [];
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
                $parameter[] =& $data[$key];
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

        $returnData = $this->selfselect($sql, $parameter, true);
        $this->clear();
        return $returnData;
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

        $returnData = $this->selfselect($sql, $this->_bindValue);
        $this->clear();
        return $returnData;
    }

    public function beginTransaction()
    {
        $result = sqlsrv_begin_transaction($this->_connect);
        if ($result === false) {
            throw new \Exception('sqlsrv_begin_transaction Exception; message : ' . json_encode(sqlsrv_errors(), JSON_UNESCAPED_UNICODE));
        }
        return $result;
    }

    public function commitTransaction()
    {
        return sqlsrv_commit($this->_connect);
    }

    public function rollbackTransaction()
    {
        return sqlsrv_rollback($this->_connect);
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
        }
        $this->_keys = implode(',', $keyArr);
        $this->_values = implode(',', $tmpArr);
        $this->_bindValue = $valueArr;
        return true;
    }

    private function uBand($data)
    {
        $keyArr = [];
        $valueArr = [];
        foreach ($data as $key => $value) {
            $keyArr[] = $key . '=? ';
            $valueArr[] =& $data[$key];
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
        }
        $this->_wheres = substr($strTmp, 4);
        if (!empty($this->_bindValue)) {
            $this->_bindValue = array_merge($this->_bindValue, $whereValueArr);
        } else {
            $this->_bindValue = $whereValueArr;
        }
        return true;
    }


    private function _order($order)
    {
        $orderArr = [];
        foreach ($order as $orderKey => $rowOrder) {
            $orderArr[] = $orderKey . ' ' . $rowOrder;
        }
        return implode(',', $orderArr);
    }

    private function sqlHtml($sql)
    {
        return '<b>' . $sql . '</b>';
    }

    /**
     * @param string $sql
     * @param array $parameter
     * @param bool $flag
     * @return array|int
     * @author LCF
     * @date 2019/9/21 20:29
     */
    public function selfselect($sql, $parameter, $flag = false)
    {
        $this->_sql = $sql;
        $this->_sqlParameter = $parameter;
        $stmt = sqlsrv_prepare($this->_connect, $sql, $parameter);
        if (!$stmt) {
            throw new \Exception('MssqlClass::selfselect exception SQL:[ ' . $this->sqlHtml($sql) . ' ]; message : ' . json_encode(sqlsrv_errors(), JSON_UNESCAPED_UNICODE), $this->_selfErrorNo);
        }
        $result = sqlsrv_execute($stmt);
        if (!$result) {
            throw new \Exception('MssqlClass::selfselect exception SQL:[ ' . $this->sqlHtml($sql) . ' ]; message : ' . json_encode(sqlsrv_errors(), JSON_UNESCAPED_UNICODE), $this->_selfErrorNo);
        }
        if ($flag === true) {
            $returnData = sqlsrv_rows_affected($stmt);
        } else {
            $returnData = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $returnData[] = $row;
            }
        }
        sqlsrv_free_stmt($stmt);
        return $returnData;
    }

    public function close()
    {
        if ($this->_connect) {
            sqlsrv_close($this->_connect);
        }
    }

    function __destruct()
    {
        if ($this->_connect) {
            sqlsrv_close($this->_connect);
        }
    }
}