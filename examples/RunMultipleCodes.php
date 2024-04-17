<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Macocci7\PhpBenchmark\Benchmark;

$iteration = 10000;

$params = [
    'haystack' => 'GPSAltitude',
    'needle' => 'GPS',
    'pattern' => sprintf("/^%s/", 'GPS'),
];

$sort = true;
$desc = false;

$callbacks = [
    'str_starts_with()' => function ($haystack, $needle, $pattern) {
        if (str_starts_with($haystack, $needle)) {
        }
    },
    'strpos()' => function ($haystack, $needle, $pattern) {
        if (strpos($haystack, $needle)) {
        }
    },
    'strpbrk()' => function ($haystack, $needle, $pattern) {
        if (strpbrk($haystack, $needle)) {
        }
    },
    'strncmp()' => function ($haystack, $needle, $pattern) {
        if (0 === strncmp($haystack, $needle, 3)) {
        }
    },
    'strstr()' => function ($haystack, $needle, $pattern) {
        if (strstr($haystack, $needle)) {
        }
    },
    'preg_match()' => function ($haystack, $needle, $pattern) {
        if (preg_match($pattern, $haystack)) {
        }
    },
    'strcmp() + substr()' => function ($haystack, $needle, $pattern) {
        if (0 === strcmp(substr($haystack, 0, 3), $needle)) {
        }
    },
    'substr_compare()' => function ($haystack, $needle, $pattern) {
        if (0 === substr_compare($haystack, $needle, 0, 3)) {
        }
    },
];

$results = Benchmark::codes($callbacks, $params, $iteration, $sort, $desc);
Benchmark::stdout($results);
