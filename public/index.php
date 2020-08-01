<?php
/**
 * @date 2019/3/13 21:50
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 * 入口文件
 */


$debugInfo = ['start_time' => $_SERVER['REQUEST_TIME_FLOAT'], 'start_memory' => memory_get_usage()];

header('Content-Type:text/html;charset=UTF-8');

include __DIR__ . '/help.php';

include __DIR__ . '/../swap/base.php';

//if (is_file(__DIR__ . '/../vendor/autoload.php')) {
//    include __DIR__ . '/../vendor/autoload.php';
//}

new \swap\core\App('pro');



$startTime = $debugInfo['start_time'];
$startMemory = $debugInfo['start_memory'];
$endTime = microtime(true);
$endMemory = memory_get_usage();
$runTime = number_format(($endTime - $startTime), 8) . ' 秒';
$usedMemory = number_format((($endMemory - $startMemory) / 1024), 6) . ' KB';
$fileLoadNum = (string)count(get_included_files()) . ' 个';
echo "<script>console.group('run.info');console.info('运行时间:','{$runTime}');console.info('内存消耗:','{$usedMemory}');console.info('文件数量:','{$fileLoadNum}');console.groupEnd('debug.end');</script>";

