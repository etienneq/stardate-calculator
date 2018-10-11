<?php

use EtienneQ\Stardate\Calculator;

require_once __DIR__.'/../vendor/autoload.php';

$calculator = new Calculator();

echo  $calculator->toStardate(new \DateTime('2364-01-02 10:56:02')).PHP_EOL;

echo  $calculator->toStardate(new \DateTime('2364-01-02 10:56:02'), 5).PHP_EOL;

echo $calculator->toGregorianDate(41209.3)->format('Y-m-d H:i:s').PHP_EOL;

echo $calculator->toGregorianDate(41209.3)->format('F j, Y').PHP_EOL;
