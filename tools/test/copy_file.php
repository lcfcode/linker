<?php

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
                echo $file . PHP_EOL;
            }
        }
    }
    closedir($dir);
}

$oldDir = 'E:/Config';
$newDir = 'C:/Users/lenovo';

$dirJetBrains = $oldDir . '/JetBrains/JetBrains';
if (is_dir($dirJetBrains)) {
    copy_dir($dirJetBrains, $newDir . '/AppData/Roaming/JetBrains');
}

$rdm = $oldDir . '/rdm';
if (is_dir($rdm)) {
//    copy_dir($rdm, $newDir);
}

$Edraw = $oldDir . '/Edraw/Edraw';
if (is_dir($Edraw)) {
//    copy_dir($Edraw, $newDir.'/AppData/Local/Edraw');
}