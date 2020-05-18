@echo off

taskkill /f /im nginx.exe
taskkill /f /im php-cgi.exe
taskkill /f /im php-cgi-spawner.exe
::taskkill /f /im redis-server.exe

taskkill /f /im php-cgi.exe
taskkill /f /im php-cgi.exe
taskkill /f /im php-cgi.exe

D:
cd \WorkSoftware\Nginx

REM set PHP_FCGI_CHILDREN=100
echo starting php-cgi ...

set PHP_HELP_MAX_REQUESTS=100
start RunHiddenConsole.exe php-cgi-spawner.exe "php\php-cgi.exe -c php\php.ini" 9000 1+16

::start RunHiddenConsole.exe php\php-cgi.exe -c php\php.ini -e -b 127.0.0.1:9000
::echo starting redis ...
::start RunHiddenConsole.exe redis\redis-server.exe
echo starting nginx ...
start nginx.exe

set ymd=%date:~0,4%%date:~5,2%%date:~8,2%
set ymd_t=%date:~0,4%%date:~5,2%%date:~8,2%%time:~0,2%%time:~3,2%%time:~6,2%
set file=F:\Users\lenovo\Desktop\log\logs\%ymd%.log
::mode con lines=5 cols=40

if NOT EXIST %file% (
	D:\WorkSoftware\Nginx\php\php.exe D:\WorkSoftware\Nginx\copy_file.php
	regedit /S D:\WorkSoftware\Nginx\regedit.reg
	echo 今天运行过了,运行开始时间：%ymd_t%>>%file%
)
::pause