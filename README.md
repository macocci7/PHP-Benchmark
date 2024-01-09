# PHP-Benchmark

A Simple Benchmark Script For PHP.

## 1. Contents

- 1\. Contents
- [2. Requirements](#2-requirements)
- [3. Installation](#3-installation)
- [4. Usage](#4-usage)
    - [4.1. Usage: Run Single Code](#41-usage-run-single-code)
    - [4.2. Usage: Run Multiple Codes](#42-usage-run-multiple-codes)
- [5. Examples](#5-examples)
- [6. LICENSE](#6-license)

## 2. Requirements

- PHP 8.0.30 or later
- (Optional) git
- (Optional) Composer

## 3. Installation

There're several options:

- Option 1: Copy [Benchmark.php](src/Benchmark.php) to your environment

    and require the code in your PHP, instead of `vendor/autoload.php`.

    This is the most simple way.

- Option 2: Clone this repository to your envenronment

    ```bash
    git clone https://github.com/macocci7/PHP-Benchmark.git
    ```

- Option 3: Download Zip from [GitHub Repository](https://github.com/macocci7/PHP-Benchmark)

    Click **[Code]** button and Click **\[Download Zip\]**.

    Then, extract zip and copy `src/Benchmark.php` to your environment.

- Option 4: Use Composer

    ```bash
    composer require macocci7/php-benchmark
    ```

## 4. Usage

### 4.1. Usage: Run Single Code

- PHP:

    ```php
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
    ```

- Result:

    ```
    1: str_starts_with() =>	Time: 0.007826 sec
    ```

### 4.2. Usage: Run Multiple Codes

- PHP:

    ```php
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
    ```

- Result:

    ```
    1:   str_starts_with() =>	Time: 0.008290 sec
    2:           strpbrk() =>	Time: 0.008492 sec
    3:            strpos() =>	Time: 0.008738 sec
    4:        preg_match() =>	Time: 0.009209 sec
    5:            strstr() =>	Time: 0.009339 sec
    6:    substr_compare() =>	Time: 0.009417 sec
    7:           strncmp() =>	Time: 0.012187 sec
    8: strcmp() + substr() =>	Time: 0.015219 sec
    ```

## 5. Examples

- [RunSingleCode.php](examples/RunSingleCode.php) >> results in [RunSingleCode.txt](examples/RunSingleCode.txt)
- [RunMultipleCodes.php](examples/RunMultipleCodes.php) >> results in [RunMultipleCodes.txt](examples/RunMultipleCodes.txt)

## 6. LICENSE

[MIT](LICENSE)

***

*Document Created: 2024/01/09*

*Document Updated: 2024/01/09*
