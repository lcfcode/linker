<?php

$client= new GearmanClient();
$client->addServer('127.0.0.1','4730');
print_r($client->doBackground("title", "hello world \n"));
echo PHP_EOL;