<?php
/**
 * Created by PhpStorm.
 * User: ChaoFu
 * Date: 2018/9/6
 * Time: 20:29
 */


function shorturl2($input) {
    $base32 = array (
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
        'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
        'y', 'z', '0', '1', '2', '3', '4', '5'
    );
    $hex = md5($input);
    $hexLen = strlen($hex);
    $subHexLen = $hexLen / 8;
    $output = array();

    for ($i = 0; $i < $subHexLen; $i++) {
//把加密字符按照8位一组16进制与0x3FFFFFFF(30位1)进行位与运算
        $subHex = substr ($hex, $i * 8, 8);
//        $int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
        $int = 0x3FFFFFFF & hexdec($subHex);
        $out = '';

        for ($j = 0; $j < 6; $j++) {

//把得到的值与0x0000001F进行位与运算，取得字符数组chars索引
            $val = 0x0000001F & $int;
            $out .= $base32[$val];
            $int = $int >> 5;
        }

        $output[] = $out;
    }

    return $output[0];
}
function shorturl($input)
{
    $base32 = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5');
    $hex = md5($input);
    $hexLen = strlen($hex);
    $subHexLen = $hexLen / 8;
    $output = array();
    for ($i = 0; $i < $subHexLen; $i++) {
        $subHex = substr($hex, $i * 8, 8);
        $int = 0x3FFFFFFF & hexdec($subHex);
        $out = '';
        for ($j = 0; $j < 6; $j++) {
            $val = 0x0000001F & $int;
            $out .= $base32[$val];
            $int = $int >> 5;
        }
        $output[] = $out;
    }
    return $output[0];
}

function orderNo($userId)
{
    $base32 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5'];
    $hex = md5($userId);
    $hexLen = strlen($hex);
    $subHexLen = $hexLen / 8;
    $output = [];
    for ($i = 0; $i < $subHexLen; $i++) {
        $subHex = substr($hex, $i * 8, 8);
        $int = 0x3FFFFFFF & hexdec($subHex);
        $out = '';
        for ($j = 0; $j < 6; $j++) {
            $val = 0x0000001F & $int;
            $out .= $base32[$val];
            $int = $int >> 5;
        }
        $output[] = $out;
    }
    $number = base_convert($output[0], 32, 10);
    return date('ymdHis') . substr(microtime(), 2, 4) . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT) . str_pad($number, 10, '0', STR_PAD_LEFT);
}

//$redis = new \Redis();
//$redis->connect('127.0.0.1', '6379');
//$redis->select(0);
//for ($i = 0; $i < 1000; $i++) {
//    echo orderNo('8888'.$i) . PHP_EOL;
//}


//$redis = new \Redis();
//$redis->connect('127.0.0.1', '6379');
//$redis->select(0);


//echo base_convert('ZZZZZZ',36,10);
//echo PHP_EOL;
$k='';
$len=100000;
$time=microtime(true);
for($i=0;$i<$len;$i++){
    $k=orderNo('8888'.$i);
    echo $k.PHP_EOL;
}
echo PHP_EOL;
$t= base_convert('ffffffffffffffffffffffffffffffff', 32, 10);
echo str_pad($t, 10, '0', STR_PAD_LEFT);
echo PHP_EOL;
print_r('orderNo-run-time:'.(microtime(true) - $time));
//$time=microtime(true);
//for($i=0;$i<$len;$i++){
//    $k=sprintf('%010d', mt_rand(26, 99));
////    $k=str_pad(mt_rand(26, 9999), 4, '0', STR_PAD_LEFT);
//}
//echo PHP_EOL;
//print_r($k.'  ||  sprintf:'.(microtime(true) - $time));
//$contents = file_get_contents('ValidateController.php');

//print_r(convertUnderline('course,course_copy1,exam,exam_copy1,student,student_copy1'));