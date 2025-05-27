<?php
/**
 * @date 2019/3/13 21:50
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 * 入口文件
 */
$startTime = $_SERVER['REQUEST_TIME_FLOAT'];
$startMemory = memory_get_usage();
header('Content-Type:text/html;charset=UTF-8');

include __DIR__ . '/help.php';
include dirname(__DIR__) . '/vendor/autoload.php';

$app = new \Swap\Core\App('dev');
$app->http();

$endTime = microtime(true);
$endMemory = memory_get_usage();
$runTime = number_format(($endTime - $startTime), 8) . ' S';
$usedMemory = number_format((($endMemory - $startMemory) / 1024), 6) . ' KB';
$fileLoadNum = (string)count(get_included_files()) . ' 个';

$requestUrl = $_SERVER['REQUEST_URI'];
$index = strpos($requestUrl, '?');
$uri = $index > 0 ? substr($requestUrl, 0, $index) : $requestUrl;
$context = json_encode([
        '运行时间' => '[' . date('Y-m-d H:i:s') . '][' . microtime() . ']',
        '运行耗时' => $runTime,
        '运行内存' => $usedMemory,
        '文件数量' => $fileLoadNum,
        '执行URL' => $uri,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
$path = dirname(__DIR__) . '/store/logs/debug';
if (!is_dir($path)) {
    mkdir($path, 0777, true);
}
$fileName = $path . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
file_put_contents($fileName, $context, FILE_APPEND | LOCK_EX);




