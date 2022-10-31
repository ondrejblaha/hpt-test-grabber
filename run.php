<?php

declare(strict_types=1);


require(__DIR__.'/src/Grabber.php');
include(__DIR__.'/src/GrabberClass.php');
require(__DIR__.'/src/Output.php');
include(__DIR__.'/src/OutputClass.php');
include(__DIR__.'/src/Dispatcher.php');

$grabber = new \HPT\GrabberClass();
$output = new \HPT\OutputClass();

$dispatcher = new \HPT\Dispatcher($grabber,$output);
echo $dispatcher->run();

