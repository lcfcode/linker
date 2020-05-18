<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/22
 * Time: 14:07
 */
$num=100000;
$k='';
$time=microtime(true);
for($i=0;$i<$num;$i++){
    $k=filectime(__FILE__);
}
print_r($k.'  ||  filectime:'.(microtime(true) - $time));
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;
$time=microtime(true);
for($i=0;$i<$num;$i++){
    $k=filectime(__FILE__);
}
print_r($k.'  ||  filectime:'.(microtime(true) - $time));
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;
$time=microtime(true);
for($i=0;$i<$num;$i++){
    $k=filemtime(__FILE__);
}
print_r($k.'  ||  filemtime:'.(microtime(true) - $time));
echo PHP_EOL;

