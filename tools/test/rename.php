<?php

$files = [];
//$path = __DIR__ . '/utils';
//searchDir($path, $files);

//foreach ($files as $file) {
//    removeNote($file);
//    echo $file . PHP_EOL;
//}

//print_r($files);

//$file = __DIR__ . '/controller/TtController.php';
//removeNote($file);

function namingStyle($file, $flag = false)
{
    //$flag ==true 表示驼峰发转下划线
    //$contents = php_strip_whitespace($file);
    $str = file_get_contents($file);
    copy($file, $file . date('YmdHis') . '_' . mt_rand(100, 999) . '.old');
    $str = recursionReplace($str, $flag);
//    $filename = $file . time() . '.php';
    $filename = $file;
    $fileHandle = fopen($filename, "w");
    flock($fileHandle, LOCK_EX);
    fwrite($fileHandle, $str);
    fclose($fileHandle);
    return true;
}


function recursionReplace($str, $flag, $num = 3)
{
    $resArr = [];
    //preg_match_all("/\\$[^\s\\]\\[=)}.,;\\-]+\s?/", $str, $res);
    preg_match_all("/\\$[^\s\\]\\[=):}.,;\\-+]+/", $str, $resArr);
    if (empty($resArr)) {
        return $str;
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
            $newName = '$' . lcfirst(trim($newName, '$'));
        }
        $str = str_replace($field, $newName, $str);
    }
    if ($num <= 0) {
        return $str;
    }
    $num--;
    return recursionReplace($str, $flag, $num);
}

function searchDir($path, &$files)
{
    $myDir = dir($path);
    while ($file = $myDir->read()) {
        if (is_file($path . '/' . $file)) {
            $files[] = $path . DIRECTORY_SEPARATOR . $file;
        }
    }
    $myDir->close();
}

function removeNote($file)
{
//    copy($file, $file . date('YmdHis') . '_' . mt_rand(100, 999) . '.old');
    $str = file_get_contents($file);
    $field = '<?php';
    $newName = $field . PHP_EOL .
        '/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    ' . PHP_EOL;

//    $newName='';
//    preg_match_all('/<\\?php((?:((?=.*namespace)).)*)/s', $str, $matches);
//    preg_match_all('/<\\?php((?:((?=.*\snamespace)).)*)/s', $str, $matches);
//    print_r($matches);die;
    $str = preg_replace("/<\\?php((?:((?=.*\snamespace)).)*)/s", $newName, $str);
    $filename = $file;
    $fileHandle = fopen($filename, "w");
    flock($fileHandle, LOCK_EX);
    fwrite($fileHandle, $str);
    fclose($fileHandle);
    return true;
}