<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Macocci7\PhpBenchmark\Benchmark;

$iteration = 10000;
$haystack = 'GPSAltitude';
$needle = 'GPS';
$pattern = sprintf("/^%s/", $needle);
$sort = true;
$desc = false;
$callbacks = [
    'str_starts_with()' => function () use ($haystack, $needle) {
        if (str_starts_with($haystack, $needle)) {
        }
    },
    'strpos()' => function () use ($haystack, $needle) {
        if (strpos($haystack, $needle)) {
        }
    },
    'strpbrk()' => function () use ($haystack, $needle) {
        if (strpbrk($haystack, $needle)) {
        }
    },
    'strncmp()' => function () use ($haystack, $needle) {
        if (0 === strncmp($haystack, $needle, 4)) {
        }
    },
    'strstr()' => function () use ($haystack, $needle) {
        if (strstr($haystack, $needle)) {
        }
    },
    'preg_match()' => function () use ($haystack, $pattern) {
        if (preg_match($pattern, $haystack)) {
        }
    },
    'strcmp() + substr()' => function () use ($haystack, $needle) {
        if (0 === strcmp(substr($haystack, 0, 4), $needle)) {
        }
    },
    'substr_compare()' => function () use ($haystack, $needle) {
        if (0 === substr_compare($haystack, $needle, 0, 4)) {
        }
    },
];

$results = Benchmark::codes($callbacks, $iteration, $sort, $desc);
Benchmark::stdout($results);
