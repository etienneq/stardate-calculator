# Stardate Calculator

PHP Converter between Star Trek's stardates and real date/time expressions.

## Prerequisites

Stardate Calculator requires PHP >= 7.2.

## Installation

Use [Composer](https://getcomposer.org/) to install this package:

```
composer require etienneq/stardate-calculator
```

## Usage

### Calculate real-world dates from stardates

```
$calculator = new \EtienneQ\Stardate\Calculator();

// outputs 2373-11-23 03:03:36
echo $calculator->toGregorianDate(50893.5)->format('Y-m-d H:i:s');
```

toGregorianDate() returns a \DateTime object which allows you to format the date/time expression as you like.

### Calculate stardates from real-world dates

```
$calculator = new \EtienneQ\Stardate\Calculator();

// outputs 41001.76
echo $calculator->toStardate(new \DateTime('2364-01-01 15:30:00'));
```

toStardate() has a second parameter which allows you to change decimal precision (defaults to 2).

```
$calculator = new \EtienneQ\Stardate\Calculator();

// outputs 41001.76457
echo $calculator->toStardate(new \DateTime('2364-01-01 15:30:00'), 5);
```