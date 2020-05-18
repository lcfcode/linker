<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/22
 * Time: 14:07
 */
$k='';
$time=microtime(true);
for($i=0;$i<1000000;$i++){
    $k=php_uname('n');
}
print_r($k.'  ||  php_uname:'.(microtime(true) - $time));
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;
$time=microtime(true);
for($i=0;$i<1000000;$i++){
    $k=gethostname();
}
print_r($k.'  ||  gethostname:'.(microtime(true) - $time));
echo PHP_EOL;
