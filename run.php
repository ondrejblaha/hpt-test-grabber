<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$grabber = new \HPT\CzcGrabber();
$output = new \HPT\ProductOutput();

$dispatcher = new \HPT\Dispatcher($grabber,$output);
$json = $dispatcher->run();

header('Content-Type: application/json; charset=utf-8');
echo $json;