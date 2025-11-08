<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpBenchmark\Benchmark;

// multiple closures
var_dump(Benchmark::getAnalyzedResults(
    callbacks: [
        'str_starts_with()' => fn () => str_starts_with('GPSAltitude', 'GPS'),
        'strpos()' => fn () => strpos('GPSAltitude', 'GPS'),
        'strpbrk()' => fn () => strpbrk('GPSAltitude', 'GPS'),
    ],
    iteration: 10000,
));
