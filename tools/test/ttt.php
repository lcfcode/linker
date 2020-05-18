<?php

$file = __DIR__ . '/User.php';

namingStyle($file);

function namingStyle($file, $flag = false)
{
    //$flag ==true 表示驼峰发转下划线
    //$contents = php_strip_whitespace($file);
    $str = file_get_contents($file);
    copy($file, $file . date('YmdHis') .'_'. mt_rand(100, 999) . '.old');
    //测试 数据
//    $str = 'dfgh $iamever_ysorry[ $@adl_fa[  $sf->s $sfsfa=k $_GET $_POST asdfss，asdfas$asdfasd$dfgaklsdfsdfdsd';
    $resArr = [];
    //preg_match_all("/\\$[^\s\\]\\[=)}.,;\\-]+\s?/", $str, $res);
    preg_match_all("/\\$[^\s\\]\\[=)}.,;\\-+]+/", $str, $resArr);

    if (empty($resArr)) {
        return false;
    }
    $varName = $resArr[0];

    //php预定义变量
    $predefine = ['$GLOBALS', '$_SERVER', '$_GET', '$_POST', '$_FILES', '$_COOKIE', '$_SESSION', '$_REQUEST', '$_ENV', '$_COOKIE', '$php_errormsg', '$HTTP_RAW_POST_DATA', '$http_response_header', '$argc', '$argv'];
    foreach ($varName as $field) {
        if (in_array(trim($field), $predefine)) {
            continue;
        }
        if ($flag === true) {
            $newName = '$' . strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $field), '$_'));
        } else {
            $newName = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $field);
        }
        $str = str_replace($field, $newName, $str);
    }

//    $filename = 'user' . time() . '.php';
    $filename = $file;
    $fileHandle = fopen($filename, "w");
    flock($fileHandle, LOCK_EX);
    fwrite($fileHandle, $str);
    fclose($fileHandle);
    return true;
}

