<?php

$worker= new GearmanWorker();
$worker->addServer('127.0.0.1','4730');
$worker->addFunction("title", "title_function");
while ($worker->work());

function title_function($job)
{
    print_r($job->workload());
    return ucwords(strtolower($job->workload()));
}