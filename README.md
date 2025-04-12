# PHP-Benchmark

A Simple, single file Benchmark Script For PHP.

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

### 4.1. Usage: Running Single Code

- PHP:


    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpBenchmark\Benchmark;

    Benchmark::code(
        name: 'str_starts_with()',
        callback: fn () => str_starts_with('GPSAltitude', 'GPS'),
        iteration: 10000
    );
    ```

- Result:

    ```
    1: str_starts_with() => Time: 0.004081 sec  Avg: 0.0000004081 sec
    ```

### 4.2. Usage: Running Multiple Codes

- PHP:

    ```php
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
        iteration: 10000,   // default: 1
        sortOrder: "",  // without sorting (default)
        //sortOrder: "asc",     // by time
        //sortOrder: "desc",
    );
    ```

- Result:

    ```
    1:   str_starts_with() => Time: 0.004008 sec  Avg: 0.0000004008 sec
    2:            strpos() => Time: 0.004001 sec  Avg: 0.0000004001 sec
    3:           strpbrk() => Time: 0.004089 sec  Avg: 0.0000004089 sec
    4:           strncmp() => Time: 0.004177 sec  Avg: 0.0000004177 sec
    5:            strstr() => Time: 0.004057 sec  Avg: 0.0000004057 sec
    6:        preg_match() => Time: 0.004803 sec  Avg: 0.0000004803 sec
    7: strcmp() + substr() => Time: 0.005728 sec  Avg: 0.0000005728 sec
    8:    substr_compare() => Time: 0.004395 sec  Avg: 0.0000004395 sec
    ```

## 5. Examples

- [RunSingleCode.php](examples/RunSingleCode.php) >> results in [RunSingleCode.txt](examples/RunSingleCode.txt)
- [RunMultipleCodes.php](examples/RunMultipleCodes.php) >> results in [RunMultipleCodes.txt](examples/RunMultipleCodes.txt)

## 6. LICENSE

[MIT](LICENSE)

Copyright 2024-2025 macocci7.
