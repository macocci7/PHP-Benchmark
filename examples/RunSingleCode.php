<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpBenchmark\Benchmark;

Benchmark::code(
    name: 'str_starts_with()',
    callback: fn () => str_starts_with('GPSAltitude', 'GPS'),
    iteration: 10000
);
