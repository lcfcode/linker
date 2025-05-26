<?php
/**
 * @date 2019/3/13 21:50
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 * 入口文件
 */

header('Content-Type:text/html;charset=UTF-8');

include dirname(__DIR__) . '/vendor/autoload.php';

$app = new \swap\core\App('pro');
$app->run();

