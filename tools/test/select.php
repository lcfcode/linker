<?php
/**
 * 配置好数据库 运行 输入指定的模块
 * 用法：please enter the module: 输入模块名；例如：mobile
 * @date 2018/8/9 16:55
 */

run();

function run()
{
    $index=isset($_POST['index'])?$_POST['index']:1;
    $pageNow=($index-1)*5;
    include 'MysqliQuery.php';
    $conn=\db\MysqliQuery::getInstance();
//    $sql = "select * from student";
    $result = $conn->query('SELECT * FROM tt WHERE a is not null');
//    print_r($result);
    $str='';
    foreach($result as $item){
        $m=preg_replace("/[0-9]||、||休闲阅读网http:\/\/www.xxyd.com\//","",$item['a']);
        $str.="'{$m}',";
    }
    echo $str;
    exit;
}

