<?php

//$url = mongodb://用户名:密码@ip:端口/库
$url = 'mongodb://user:pass@localhost:27017';
$url = 'mongodb://192.168.31.89:27017';
$url = 'mongodb://localhost:27017';
$opt = ['username' => 'admin', 'password' => 'Abcd4321'];
//$url='mongodb://localhost:7017';
//$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
//['user'=>'username', 'pass'=> 'password'];
//链接mongodb
$manager = new MongoDB\Driver\Manager($url, $opt);
//$r=$manager->executeCommand('lcf_test', new MongoDB\Driver\Command(["create"=>'xxxxx']));
//$r=$manager->executeCommand('lcf_test', new MongoDB\Driver\Command(["drop"=>'xxxxx']));//删除集合
//链接mongodb
//$manager = new MongoDB\Driver\Manager('mongodb://root:sjhc168@10.10.10.104:27017');

//查询
$filter = ['user_id' => ['$gt' => 0]]; //查询条件 user_id大于0

$options = [
    'projection' => ['_id' => 0], //不输出_id字段
    'sort' => ['user_id' => -1] //根据user_id字段排序 1是升序，-1是降序
];

$query = new MongoDB\Driver\Query($filter, $options); //查询请求

$list = $manager->executeQuery('location.box', $query); // 执行查询 location数据库下的box集合

foreach ($list as $document) {
//    print_r($document);
    print_r(get_object_vars($document));
}
//插入操作
//$bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
//$bulk->insert(['user_id' => 2, 'real_name' => '中国',]);
//$bulk->insert(['user_id' => 3, 'real_name' => '中国人',]);

//$result = $manager->executeBulkWrite('location.box', $bulk); //执行写入 location数据库下的box集合
//print_r($result);

//更新
$bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
/**
 * 1：默认是ture，按照顺序执行插入更新数据，如果出错，停止执行后面的，mongo官方叫串行。
 * 2：如果是false，mongo并发的方式插入更新数据，中间出现错误，不影响后续操作无影响，mongo官方叫并行
 */
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
//$bulk->update(
//    ['user_id' => 2],
//    ['$set' => ['real_name' => '中国国']
//    ]);
//$set相当于mysql的 set，这里和mysql有两个不同的地方，
//1：字段不存在会添加一个字段;
//2：mongodb默认如果条件不成立，新增加数据，相当于insert
//如果条件不存在不新增加，可以通过设置upsert
//db.collectionName.update(query, obj, upsert, multi);
//$bulk->update(
//    ['user_id' => 5],
//    [
//        '$set' => ['fff' => '中国国']
//    ],
//    ['multi' => true, 'upsert' => true]
//);
//multi为true,则满足条件的全部修改,默认为false，如果改为false，则只修改满足条件的第一条
//upsert为 true：表示不存在就新增

//$result = $manager->executeBulkWrite('location.box', $bulk); //执行写入 location数据库下的box集合
//print_r($result);


//删除
//$bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
//$bulk->delete(['user_id' => 5]);//删除user_id为5的字段
//$bulk->delete(['user_id' => 2], ['limit' => 1]);   // limit 为 1 时，删除第一条匹配数据
//$bulk->delete(['user_id' => 2], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据，默认删除
//$result = $manager->executeBulkWrite('location.box', $bulk); //执行写入 location数据库下的box集合
//print_r($result);




