<?php

$str = <<<DDD
@echo off
chcp 65001
cd /d %~dp0

git pull
git add .
git commit -m "bat_commit: %date% %time%"
git push origin master
git push gitee master
rem pause
rem timeout /t 5 /nobreak

DDD;
file_put_contents(dirname(__DIR__) . '/.gitpush.bat', $str);
$str = <<<DDD
@echo off
chcp 65001
cd /d %~dp0
git pull origin master
git pull gitee master

rem timeout /t 5 /nobreak

DDD;
file_put_contents(dirname(__DIR__) . '/.gitpull.bat', $str);
echo 'success' . PHP_EOL;