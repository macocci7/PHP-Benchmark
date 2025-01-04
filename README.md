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

- PHP 8.1 or later
- (Optional) git
- (Optional) Composer

## 3. Installation

There're several options:

- Option 1: Copy or download [Benchmark.php](src/Benchmark.php) to your environment

    and `require` the code in your PHP, instead of `vendor/autoload.php`.

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

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpBenchmark\Benchmark;

    $iteration = 10000;

    $haystack = 'GPSAltitude';
    $needle = 'GPS';

    $name = 'str_starts_with()';
    $callback = function ($haystack, $needle) {
        if (str_starts_with($haystack, $needle)) {
        }
    };
    $params = [ 'haystack' => $haystack, 'needle' => $needle, ];

    $result = Benchmark::code($name, $callback, $params, $iteration);
    Benchmark::stdout($result);
    ```

- Result:

    ```
    1: str_starts_with() =>	Time: 0.008332 sec
    ```

### 4.2. Usage: Run Multiple Codes

- PHP:

    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

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
    ```

- Result:

    ```
    1:   str_starts_with() =>	Time: 0.008634 sec
    2:           strpbrk() =>	Time: 0.008911 sec
    3:           strncmp() =>	Time: 0.009266 sec
    4:            strstr() =>	Time: 0.009594 sec
    5:            strpos() =>	Time: 0.009826 sec
    6:        preg_match() =>	Time: 0.011233 sec
    7:    substr_compare() =>	Time: 0.011373 sec
    8: strcmp() + substr() =>	Time: 0.012719 sec
    ```

## 5. Examples

- [RunSingleCode.php](examples/RunSingleCode.php) >> results in [RunSingleCode.txt](examples/RunSingleCode.txt)
- [RunMultipleCodes.php](examples/RunMultipleCodes.php) >> results in [RunMultipleCodes.txt](examples/RunMultipleCodes.txt)

## 6. LICENSE

[MIT](LICENSE)

***

*Document Created: 2024/01/09*

*Document Updated: 2025/01/04*

Copyright 2024-2025 macocci7.
