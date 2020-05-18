<?php
/**
 * Created by PhpStorm.
 * User: ChaoFu
 * Date: 2018/9/16
 * Time: 17:33
 */


$str=<<<LLL












LLL;

echo PHP_EOL.PHP_EOL;
echo PHP_EOL.PHP_EOL;

echo strip($str);

echo PHP_EOL.PHP_EOL;


function strip($str)
{
    $str=str_replace("<br>","",$str);
    $str=str_replace("&nbsp;","",$str);
//$str=htmlspecialchars($str);
    return strip_tags($str);
}