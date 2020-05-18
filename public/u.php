<?php

//print_r(scandir(__DIR__));

die;
$r=[
    'id' => 'id', 'name' => 'name', 'phone' => 'phone', 'job_number' => 'job_number', 'card_id' => 'card_id',
    'sex' => 'sex', 'age' => 'age', 'birthday' => 'birthday', 'head_img' => 'head_img', 'mail' => 'mail',
    'specialty' => 'specialty', 'area' => 'area', 'qq' => 'qq', 'is_enable' => 'is_enable', 'source' => 'source',
    'create_time' => 'create_time', 'update_time' => 'update_time',
];

$r['id']='123456';
print_r($r);

echo PHP_EOL;

echo implode(',',$r);







function ii()
{
    $strx = 'PPPasa_kjkklkjklkljkl_klfKslasKdNLLdsdsasdAWE';

    echo strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $str), "_"));

    echo PHP_EOL;

    echo tt($str);

    echo PHP_EOL;
}



function tt($name,$ucfirst=true){
    $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
        return strtoupper($match[1]);
    }, $name);

    return $ucfirst ? ucfirst($name) : lcfirst($name);
}