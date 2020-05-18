<?php

$dir='/ad/asd/asd/';
$tt=trim($dir, "/\\");

print_r($tt);
die;
include '../vendor/autoload.php';
header('Content-Type:text/html;charset=UTF-8');
//Kint::dump($GLOBALS, $_SERVER); // Dump any number of variables
//d($GLOBALS, $_SERVER); // d() is a shortcut for Kint::dump()
//d('ccccccccccc'); // d() is a shortcut for Kint::dump()

//Kint::trace(); // Dump a debug backtrace
//d(1); // Shortcut for Kint::trace()


//$whoops = new \Whoops\Run;
//$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
//$whoops->register();

debug_backtrace();
//krumo::$skin = 'orange';
//
//krumo($_SERVER, $_ENV);

$mm=[];
$d=$mm['x'];