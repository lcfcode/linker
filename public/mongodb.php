<?php

//$db = (new MongoDB\Client)->demo;
//
//$result = $db->dropCollection('users');
//var_dump($result);
//$url = mongodb://用户名:密码@ip:端口/库
$url = 'mongodb://user:pass@localhost:27017';
$url = 'mongodb://192.168.31.89:27017';
$url = 'mongodb://localhost:27017';
$opt = ['username' => 'admin', 'password' => 'Abcd4321'];
//$url='mongodb://localhost:7017';
//$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
//['user'=>'username', 'pass'=> 'password'];
$manager = new MongoDB\Driver\Manager($url, $opt);


//$manager = new \MongoDB\Driver\Manager("mongodb://" . $username . ":" . $password . "@{$host}:{$port}");
//lcf_test.sites
//$r=$manager->executeCommand('lcf_test', new MongoDB\Driver\Command(["create"=>'xxxxx']));
$r=$manager->executeCommand('lcf_test', new MongoDB\Driver\Command(["drop"=>'xxxxx']));//删除集合
print_r($r);die;
//var_dump($manager);die;

// 插入数据
//$bulk = new MongoDB\Driver\BulkWrite;
//$bulk->insert(['x' => 1, 'name' => '菜鸟教程', 'url' => 'http://www.runoob.com']);
//$bulk->insert(['x' => 2, 'name' => 'Google', 'url' => 'http://www.google.com']);
//$bulk->insert(['x' => 3, 'name' => 'taobao', 'url' => 'http://www.taobao.com']);
//$manager->executeBulkWrite('lcf_test.sites', $bulk);

$filter = ['x' => ['$gt' => 1]];
$options = [
    'projection' => ['_id' => 0],
    'sort' => ['x' => -1],
];

// 查询数据
//$query = new MongoDB\Driver\Query($filter, $options);
//$cursor = $manager->executeQuery('lcf_test.sites', $query);
//print_r($cursor);
//$cursor=$cursor->toArray();
//print_r($cursor);die;
//foreach ($cursor as $document) {
//    print_r(get_object_vars($document));
//    print_r($document);
//}


$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete([]);   //删除所有
//$bulk->delete(['x' => 1], ['limit' => 1]);   // limit 为 1 时，删除第一条匹配数据
//$bulk->delete(['x' => 2], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据

$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('lcf_test.sites', $bulk, $writeConcern);
print_r($result);


