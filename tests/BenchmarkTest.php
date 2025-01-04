<?php

declare(strict_types=1);

namespace Macocci7\PhpBenchmark;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpBenchmark\Benchmark;

final class BenchmarkTest extends TestCase
{
    public static function provide_run_can_return_float_correctly(): array
    {
        return [
            "fn (), iteration: null" => [ 'callback' => fn ($i) => $i++, 'params' => ['i' => 0], 'iteration' => null, ],
            "fn (), iteration: 1" => [ 'callback' => fn ($i) => $i++, 'params' => ['i' => 0], 'iteration' => 1, ],
            "fn (), iteration: 10000" => [ 'callback' => fn ($i) => $i++, 'params' => ['i' => 0], 'iteration' => 10000, ],
            "funciton (), iteration: 1" => [
                'callback' => function ($i) {
                    $i++;
                },
                'params' => ['i' => 0],
                'iteration' => 1,
            ],
        ];
    }

    #[DataProvider('provide_run_can_return_float_correctly')]
    public function test_run_can_return_float_correctly(\Closure $callback, array $params, int|null $iteration): void
    {
        $result = is_null($iteration) ? Benchmark::run($callback, $params) : Benchmark::run($callback, $params, $iteration);
        $this->assertTrue(is_float($result));
        $this->assertTrue($result >= 0);
    }

    public static function provide_code_can_return_array_correctly(): array
    {
        return [
            "fn (), iteration: null" => [
                'name' => 'fn (), iteration: null',
                'callback' => fn ($i) => $i++,
                'params' => ['i' => 0],
                'iteration' => null,
            ],
            "fn (), iteration: 1" => [
                'name' => 'fn (), iteration: 1',
                'callback' => fn ($i) => $i++,
                'params' => ['i' => 0],
                'iteration' => 1,
            ],
            "fn (), iteration: 10000" => [
                'name' => 'fn (), iteration: 10000',
                'callback' => fn ($i) => $i++,
                'params' => ['i' => 0],
                'iteration' => 10000,
            ],
            "function (), iteration: 1" => [
                'name' => 'function (), iteration: 1',
                'callback' => function ($i) {
                    $i++;
                },
                'params' => ['i' => 0],
                'iteration' => 1,
            ],
        ];
    }

    #[DataProvider('provide_code_can_return_array_correctly')]
    public function test_code_can_run_code_correctly(string $name, \Closure $callback, array $params, int|null $iteration): void
    {
        $result = is_null($iteration)
                ? Benchmark::code($name, $callback, $params)
                : Benchmark::code($name, $callback, $params, $iteration)
                ;
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) === 1);
        $this->assertTrue(isset($result[$name]));
        $this->assertTrue(is_float($result[$name]));
        $this->assertTrue($result[$name] >= 0);
    }

    public static function provide_codes_can_throw_exception_with_invalid_param(): array
    {
        return [
            "callbacks:empty, iteration:null, sort:null, desc:null" => [
                'callbacks' => [],
                'params' => ['i' => 0],
            ],
        ];
    }

    #[DataProvider('provide_codes_can_throw_exception_with_invalid_param')]
    public function test_codes_can_throw_exception_with_invalid_param(
        array $callbacks,
        array $params
    ): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("empty codes.");
        Benchmark::codes($callbacks, $params);
    }

    public static function provide_codes_can_return_results_correctly(): array
    {
        return [
            "callbacks:fn, iteration:null, sort:null, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => null,
                'sort' => null,
                'desc' => null,
            ],
            "callbacks:fn, iteration:1, sort:null, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 1,
                'sort' => null,
                'desc' => null,
            ],
            "callbacks:fn, iteration:100, sort:true, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 100,
                'sort' => true,
                'desc' => null,
            ],
            "callbacks:fn, iteration:100, sort:false, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 100,
                'sort' => false,
                'desc' => null,
            ],
            "callbacks:fn, iteration:100, sort:true, desc:true" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 100,
                'sort' => true,
                'desc' => true,
            ],
            "callbacks:fn, iteration:100, sort:true, desc:false" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 100,
                'sort' => true,
                'desc' => false,
            ],
            "callbacks:fn, iteration:100, sort:false, desc:true" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 100,
                'sort' => false,
                'desc' => true,
            ],
            "callbacks:fn, iteration:100, sort:false, desc:false" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'iteration' => 100,
                'sort' => false,
                'desc' => false,
            ],
        ];
    }

    #[DataProvider('provide_codes_can_return_results_correctly')]
    public function test_codes_can_return_results_correctly(
        array $callbacks,
        array $params,
        int|null $iteration,
        bool|null $sort,
        bool|null $desc
    ): void {
        $result = null;
        if (is_null($iteration)) {
            $result = Benchmark::codes($callbacks, $params);
        } elseif (is_null($sort) && is_null($desc)) {
            $result = Benchmark::codes($callbacks, $params, $iteration);
        } elseif (is_null($desc)) {
            $result = Benchmark::codes($callbacks, $params, $iteration, $sort);
        } elseif (!is_null($sort) && !is_null($desc)) {
            $result = Benchmark::codes($callbacks, $params, $iteration, $sort, $desc);
        }
        $this->assertTrue(is_array($result));
        $this->assertTrue(!empty($result));
        $callbacksKeys = array_keys($callbacks);
        $resultKeys = array_keys($result);
        sort($callbacksKeys);
        sort($resultKeys);
        $this->assertSame($callbacksKeys, $resultKeys);
        foreach ($result as $value) {
            $this->assertTrue(is_float($value));
            $this->assertTrue($value >= 0);
        }
    }
}
