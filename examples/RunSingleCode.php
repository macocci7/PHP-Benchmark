<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Macocci7\PhpBenchmark\Benchmark;

$iteration = 10000;
$haystack = 'GPSAltitude';
$needle = 'GPS';
$name = 'str_starts_with()';
$callback = function () use ($haystack, $needle) {
    if (str_starts_with($haystack, $needle)) {
    }
};

$result = Benchmark::code($name, $callback, $iteration);
Benchmark::stdout($result);
