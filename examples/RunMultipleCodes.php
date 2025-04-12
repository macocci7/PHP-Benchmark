<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpBenchmark\Benchmark;

Benchmark::codes(
    callbacks: [
        'str_starts_with()' => fn () => str_starts_with('GPSAltitude', 'GPS'),
        'strpos()' => fn () => strpos('GPSAltitude', 'GPS'),
        'strpbrk()' => fn () => strpbrk('GPSAltitude', 'GPS'),
        'strncmp()' => fn () => 0 === strncmp('GPSAltitude', 'GPS', 3),
        'strstr()' => fn () => strstr('GPSAltitude', 'GPS'),
        'preg_match()' => fn () => preg_match('/^GPS/', 'GPSAltitude'),
        'strcmp() + substr()' => fn () => 0 === strcmp(substr('GPSAltitude', 0, 3), 'GPS'),
        'substr_compare()' => fn () => 0 === substr_compare('GPSAltitude', 'GPS', 0, 3),
    ],
    iteration: 10000,
    sortOrder: "",  // without sorting
    //sortOrder: "asc",
    //sortOrder: "desc",
);
