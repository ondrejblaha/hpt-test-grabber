<?php

declare(strict_types=1);


include(__DIR__.'/src/Grabber.php');
include(__DIR__.'/src/Output.php');
include(__DIR__.'/src/Dispatcher.php');

$grabber = new \HPT\Grabber();
$output = new \HPT\Output();

$dispatcher = new \HPT\Dispatcher($grabber,$output);
echo $dispatcher->run();

