<?php


foreachDir(__DIR__ . '/activettt', 'shtml', 'phtml');





/**
 * 批量修改文件后缀名
 * @param $path string 文件夹路径
 * @param $sext string 原文件后缀名
 * @param $dext string 目的文件后缀名
 * @return void
 */
function foreachDir($path, $sext, $dext)
{
    $handle = dir($path);
    while ($file = $handle->read()) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (is_dir($path . '/' . $file)) {
            foreachDir($path . '/' . $file, $sext, $dext);
        }
        if (is_file($path . '/' . $file)) {
            $ext = strripos($file, '.');
            $suffix = substr($file, $ext + 1);
            if ($sext == $suffix) {
                $fileName = substr($file, 0, $ext);
                $src = $path . '/' . $file;
                $dest = $path . '/' . $fileName . '.' . $dext;
                rename($src, $dest);
                echo $src . '=========>' . $dest . PHP_EOL;
            }
        }
    }
    $handle->close();
}
