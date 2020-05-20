<?php
/**
 * @date 2019/3/13 21:50
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 * 入口文件
 */

use app\AppException;
use swap\linker\App;

header('Content-Type:text/html;charset=UTF-8');

include __DIR__ . '/help.php';

include __DIR__ . '/../swap/base.php';

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    include __DIR__ . '/../vendor/autoload.php';
}

$setConfig = [
    'err_obj' => new AppException(),//异常处理类 已经实例化过的
];

new App('dev', $setConfig);