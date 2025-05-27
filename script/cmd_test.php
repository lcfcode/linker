<?php

use Swap\Core\App;
use Swap\Core\Db;

include dirname(__DIR__) . '/vendor/autoload.php';

$app = new App('dev');

$db = Db::instance($app);
$arr = $db->selects('course');
print_r($app->config());
//$redis = $db->getRedis();
//$redis->set('lll','lian');
//print_r($redis->get('lll'));
$app->logs($arr);
//print_r($db->clientInfo());

