<?php

//移除bom头

$basedir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';

checkDir($basedir, true);

function checkDir($basedir, $auto = false)
{
    if ($dh = opendir($basedir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') {
                if (!is_dir($basedir . DIRECTORY_SEPARATOR . $file)) {
                    checkBOM($basedir . DIRECTORY_SEPARATOR . $file, $auto);
                } else {
                    $dirname = $basedir . DIRECTORY_SEPARATOR . $file;
                    checkDir($dirname, $auto);
                }
            }
        }
        closedir($dh);
    }
}

function checkBOM($filename, $auto)
{
    $contents = file_get_contents($filename);
    $charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);
    if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
        if ($auto == true) {
            $rest = substr($contents, 3);
            rewrite($filename, $rest);
            echo $filename . ' -- BOM found, automatically removed. ' . PHP_EOL;
        } else {
            echo $filename . ' -- BOM found. ' . PHP_EOL;
        }
    }
}

function rewrite($filename, $data)
{
    $filenum = fopen($filename, "w");
    flock($filenum, LOCK_EX);
    fwrite($filenum, $data);
    fclose($filenum);
}