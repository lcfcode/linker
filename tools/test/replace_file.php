<?php
/**
 * @param $src
 * @param $dst
 * @date 2018/8/22 10:01
 * @功能 统一替换文件使用，1是不依赖composer情况下减少性能开销；2是方便升级
 */
function copy_dir($src, $dst)
{
    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst, 0777, true);
    }
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                copy_dir($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                continue;
            } else {
                copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                echo $src . DIRECTORY_SEPARATOR . $file . ' ===>>> ' . $dst . DIRECTORY_SEPARATOR . $file . PHP_EOL;
            }
        }
    }
    closedir($dir);
}

$dirOld = __DIR__ . '/../../scanlib';
if ($dirOld) {
    copy_dir($dirOld, __DIR__ . '/../../../linkerlib');
}