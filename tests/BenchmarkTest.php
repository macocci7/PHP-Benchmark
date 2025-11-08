<?php

declare(strict_types=1);

namespace Macocci7\PhpBenchmark;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpBenchmark\Benchmark;

final class BenchmarkTest extends TestCase
{
    public static function provide_run_can_return_result_correctly(): array
    {
        return [
            "fn (), iteration: null" => [ 'callback' => fn () => true, 'iteration' => null, ],
            "fn (), iteration: 1" => [ 'callback' => fn () => true, 'iteration' => 1, ],
            "fn (), iteration: 10000" => [ 'callback' => fn () => true, 'iteration' => 10000, ],
            "funciton (), iteration: 1" => [
                'callback' => function () {
                    $i = 0;
                    $i++;
                },
                'iteration' => 1,
            ],
        ];
    }

    #[DataProvider('provide_run_can_return_result_correctly')]
    public function test_run_can_return_result_correctly(\Closure $callback, int|null $iteration): void
    {
        $result = is_null($iteration) ? Benchmark::run($callback) : Benchmark::run($callback, $iteration);
        $this->assertTrue(is_float($result['time']));
        $this->assertTrue(is_float($result['average']));
        $this->assertFalse($result['time'] < 0);
        $this->assertFalse($result['average'] < 0);
    }

    public static function provide_code_can_return_array_correctly(): array
    {
        return [
            "fn (), iteration: null" => [
                'name' => 'fn (), iteration: null',
                'callback' => fn () => true,
                'iteration' => null,
            ],
            "fn (), iteration: -1" => [
                'name' => 'fn (), iteration: -1',
                'callback' => fn () => true,
                'iteration' => -1,
            ],
            "fn (), iteration: 0" => [
                'name' => 'fn (), iteration: -1',
                'callback' => fn () => true,
                'iteration' => 0,
            ],
            "fn (), iteration: 1" => [
                'name' => 'fn (), iteration: 1',
                'callback' => fn () => true,
                'iteration' => 1,
            ],
            "fn (), iteration: 10000" => [
                'name' => 'fn (), iteration: 10000',
                'callback' => fn () => true,
                'iteration' => 10000,
            ],
            "function (), iteration: 1" => [
                'name' => 'function (), iteration: 1',
                'callback' => function () {
                    return true;
                },
                'iteration' => 1,
            ],
        ];
    }

    #[DataProvider('provide_code_can_return_array_correctly')]
    public function test_code_can_run_code_correctly(string $name, \Closure $callback, int|null $iteration): void
    {
        $pattern = sprintf(
            '/^1\: %s => Time\: [0-9\.]+ sec  Avg\: [0-9\.]+ sec$/',
            preg_quote($name),
        );

        $this->expectOutputRegex($pattern);
        is_null($iteration)
                ? Benchmark::code($name, $callback)
                : Benchmark::code($name, $callback, $iteration);
    }
    public static function provide_codes_can_throw_exception_with_invalid_param(): array
    {
        return [
            "callbacks:empty, iteration:null, sort:null, desc:null" => [
                'callbacks' => [],
            ],
        ];
    }

    #[DataProvider('provide_codes_can_throw_exception_with_invalid_param')]
    public function test_codes_can_throw_exception_with_invalid_param(array $callbacks): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("empty codes.");
        Benchmark::codes($callbacks);
    }

    public static function provide_codes_can_return_results_correctly(): array
    {
        return [
            "callbacks:fn, iteration:null, sort:null" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => null,
                'sort' => null,
            ],
            "callbacks:fn, iteration:1, sort:null" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => 1,
                'sort' => null,
            ],
            "callbacks:fn, iteration:100, sort:asc" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => 100,
                'sort' => "asc",
            ],
            "callbacks:fn, iteration:100, sort:''" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => 100,
                'sort' => '',
            ],
            "callbacks:fn, iteration:100, sort:desc" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => 100,
                'sort' => "desc",
            ],
            "callbacks:fn, iteration:100, sort:asc" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => 100,
                'sort' => "asc",
            ],
            "callbacks:fn, iteration:100, sort:foo" => [
                'callbacks' => [
                    "fn () 1" => fn () => 1,
                    "fn () 2" => fn () => 2,
                    "fn () 3" => fn () => 3,
                ],
                'iteration' => 100,
                'sort' => "foo",
            ],
        ];
    }

    #[DataProvider('provide_codes_can_return_results_correctly')]
    public function test_codes_can_return_results_correctly(
        array $callbacks,
        int|null $iteration,
        string|null $sort,
    ): void {

        $patterns = [];
        $i = 0;
        foreach ($callbacks as $name => $callback) {
            $i++;
            $patterns[] = sprintf(
                '[0-9 ]+\:[ ]+%s => Time\: [0-9\.]+ sec  Avg\: [0-9\.]+ sec\n',
                preg_quote($name),
            );
        }
        $pattern = '/' . implode('|', $patterns) . '/';

        $this->expectOutputRegex($pattern);

        if (is_null($iteration)) {
            Benchmark::codes($callbacks);
        } elseif (is_null($sort)) {
            Benchmark::codes($callbacks, $iteration);
        } else {
            Benchmark::codes($callbacks, $iteration, $sort);
        }
    }

    public function test_getResults_can_return_results_correctly(): void
    {
        $result = Benchmark::getResults(
            callbacks: [
                'str_starts_with()' => fn () => str_starts_with('GPSAltitude', 'GPS'),
                'strpos()' => fn () => strpos('GPSAltitude', 'GPS'),
            ],
            iteration: 100,
        );
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) === 2);
        $this->assertEquals(
            ['str_starts_with()', 'strpos()'],
            array_keys($result),
        );
        foreach ($result as $key => $res) {
            $this->assertArrayHasKey('time', $res);
            $this->assertArrayHasKey('average', $res);
            $this->assertTrue(is_float($res['time']));
            $this->assertTrue(is_float($res['average']));
            $this->assertFalse($res['time'] < 0);
            $this->assertFalse($res['average'] < 0);
        }
    }

    public function test_getAnalyzedResults_can_return_results_correctly(): void
    {
        $result = Benchmark::getAnalyzedResults(
            callbacks: [
                'str_starts_with()' => fn () => str_starts_with('GPSAltitude', 'GPS'),
                'strpos()' => fn () => strpos('GPSAltitude', 'GPS'),
                'strpbrk()' => fn () => strpbrk('GPSAltitude', 'GPS'),
            ],
            iteration: 100,
        );
        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result["fastest"]));
        $fastestKey = array_keys($result["fastest"])[0];
        $slowestKey = array_keys($result["slowest"])[0];
        $fastest = $result["fastest"][$fastestKey]["time"];
        $slowest = $result["slowest"][$slowestKey]["time"];
        $this->assertTrue(is_float($fastest));
        $this->assertTrue(is_float($slowest));
        $this->assertTrue(count($result["details"]) === 3);
        $this->assertEquals(
            ['str_starts_with()', 'strpos()', 'strpbrk()'],
            array_keys($result["details"]),
        );
        foreach ($result["details"] as $key => $res) {
            $this->assertArrayHasKey('time', $res);
            $this->assertArrayHasKey('average', $res);
            $this->assertTrue(is_float($res['time']));
            $this->assertTrue(is_float($res['average']));
            $this->assertFalse($res['time'] < 0);
            $this->assertFalse($res['average'] < 0);
            $this->assertTrue($res["time"] >= $fastest);
            $this->assertTrue($res["time"] <= $slowest);
            $this->assertTrue(is_float($res["relative_to_fastest"]));
            $this->assertTrue(is_float($res["relative_to_slowest"]));
            $this->assertTrue($res["relative_to_fastest"] >= 1);
            $this->assertTrue($res["relative_to_slowest"] <= 1);
        }
        $this->assertSame((float) 1, $result["details"][$fastestKey]["relative_to_fastest"]);
        $this->assertSame((float) 1, $result["details"][$slowestKey]["relative_to_slowest"]);
    }
}
