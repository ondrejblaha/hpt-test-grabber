<?php

declare(strict_types=1);

use HPT\CzcGrabber;
use HPT\Dispatcher;
use HPT\ProductOutput;

require_once __DIR__ . '/vendor/autoload.php';

$grabber = new CzcGrabber();
$output = new ProductOutput();

$dispatcher = new Dispatcher($grabber,$output);
$json = $dispatcher->run();

header('Content-Type: application/json; charset=utf-8');
echo $json;