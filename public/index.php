<?php
/**
 * @date 2019/3/13 21:50
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 * 入口文件
 */

$startTime=$_SERVER['REQUEST_TIME_FLOAT'];
$startMemory=memory_get_usage();

header('Content-Type:text/html;charset=UTF-8');

include __DIR__ . '/help.php';

include __DIR__ . '/../swap/base.php';

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    include __DIR__ . '/../vendor/autoload.php';
}

$app = new \swap\core\App('dev');
$app->run();

/********************测试*******************************/
$endTime = microtime(true);
$endMemory = memory_get_usage();
$runTime = number_format(($endTime - $startTime), 8) . ' 秒';
$usedMemory = number_format((($endMemory - $startMemory) / 1024), 6) . ' KB';
$fileLoadNum = (string)count(get_included_files()) . ' 个';

$requestUrl = $_SERVER['REQUEST_URI'];
$index = strpos($requestUrl, '?');
$uri = $index > 0 ? substr($requestUrl, 0, $index) : $requestUrl;
$context = json_encode([
        'log_date' => '[' . date('Y-m-d H:i:s') . '][' . microtime() . ']',
        'run_time' => $runTime,
        'used_memory' => $usedMemory,
        'file_load_num' => $fileLoadNum,
        'url' => $uri,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
$fileName = 'debug_' . date('Y-m-d') . '.log';
file_put_contents($fileName, $context, FILE_APPEND | LOCK_EX);

