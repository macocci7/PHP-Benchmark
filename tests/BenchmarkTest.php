<?php   // phpcs:ignore

declare(strict_types=1);

namespace Macocci7\PhpBenchmark;

require('vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Macocci7\PhpBenchmark\Benchmark;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
final class BenchmarkTest extends TestCase
{
    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    // phpcs:disable Generic.Files.LineLength.TooLong

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public static function provide_run_can_return_float_correctly(): array
    {
        return [
            "fn (), itelation: null" => [ 'callback' => fn ($i) => $i++, 'params' => ['i' => 0], 'itelation' => null, ],
            "fn (), itelation: 1" => [ 'callback' => fn ($i) => $i++, 'params' => ['i' => 0], 'itelation' => 1, ],
            "fn (), itelation: 10000" => [ 'callback' => fn ($i) => $i++, 'params' => ['i' => 0], 'itelation' => 10000, ],
            "funciton (), itelation: 1" => [
                'callback' => function ($i) {
                    $i++;
                },
                'params' => ['i' => 0],
                'itelation' => 1,
            ],
        ];
    }

    /**
     * @dataProvider provide_run_can_return_float_correctly
     */
    public function test_run_can_return_float_correctly(\Closure $callback, array $params, int|null $itelation): void
    {
        $result = is_null($itelation) ? Benchmark::run($callback, $params) : Benchmark::run($callback, $params, $itelation);
        $this->assertTrue(is_float($result));
        $this->assertTrue($result > 0);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public static function provide_code_can_return_array_correctly(): array
    {
        return [
            "fn (), itelation: null" => [
                'name' => 'fn (), itelation: null',
                'callback' => fn ($i) => $i++,
                'params' => ['i' => 0],
                'itelation' => null,
            ],
            "fn (), itelation: 1" => [
                'name' => 'fn (), itelation: 1',
                'callback' => fn ($i) => $i++,
                'params' => ['i' => 0],
                'itelation' => 1,
            ],
            "fn (), itelation: 10000" => [
                'name' => 'fn (), itelation: 10000',
                'callback' => fn ($i) => $i++,
                'params' => ['i' => 0],
                'itelation' => 10000,
            ],
            "function (), itelation: 1" => [
                'name' => 'fn (), itelation: null',
                'callback' => function ($i) {
                    $i++;
                },
                'params' => ['i' => 0],
                'itelation' => 1,
            ],
        ];
    }

    /**
     * @dataProvider provide_code_can_return_array_correctly
     */
    public function test_code_can_run_code_correctly(string $name, \Closure $callback, array $params, int|null $itelation): void
    {
        $result = is_null($itelation)
                ? Benchmark::code($name, $callback, $params)
                : Benchmark::code($name, $callback, $params, $itelation)
                ;
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) === 1);
        $this->assertTrue(isset($result[$name]));
        $this->assertTrue(is_float($result[$name]));
        $this->assertTrue($result[$name] > 0);
    }

    public static function provide_codes_can_throw_exception_with_invalid_param(): array
    {
        return [
            "callbacks:empty, itelation:null, sort:null, desc:null" => [
                'callbacks' => [],
                'params' => ['i' => 0],
            ],
        ];
    }

    /**
     * @dataProvider provide_codes_can_throw_exception_with_invalid_param
     */
    public function test_codes_can_throw_exception_with_invalid_param(
        array $callbacks,
        array $params
    ): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("empty codes.");
        Benchmark::codes($callbacks, $params);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public static function provide_codes_can_return_results_correctly(): array
    {
        return [
            "callbacks:fn, itelation:null, sort:null, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => null,
                'sort' => null,
                'desc' => null,
            ],
            "callbacks:fn, itelation:1, sort:null, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 1,
                'sort' => null,
                'desc' => null,
            ],
            "callbacks:fn, itelation:100, sort:true, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 100,
                'sort' => true,
                'desc' => null,
            ],
            "callbacks:fn, itelation:100, sort:false, desc:null" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 100,
                'sort' => false,
                'desc' => null,
            ],
            "callbacks:fn, itelation:100, sort:true, desc:true" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 100,
                'sort' => true,
                'desc' => true,
            ],
            "callbacks:fn, itelation:100, sort:true, desc:false" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 100,
                'sort' => true,
                'desc' => false,
            ],
            "callbacks:fn, itelation:100, sort:false, desc:true" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 100,
                'sort' => false,
                'desc' => true,
            ],
            "callbacks:fn, itelation:100, sort:false, desc:false" => [
                'callbacks' => [
                    "fn () 1" => fn ($i) => $i += 1,
                    "fn () 2" => fn ($i) => $i += 2,
                    "fn () 3" => fn ($i) => $i += 3,
                ],
                'params' => ['i' => 0],
                'itelation' => 100,
                'sort' => false,
                'desc' => false,
            ],
        ];
    }

    /**
     * @dataProvider provide_codes_can_return_results_correctly
     */
    public function test_codes_can_return_results_correctly(
        array $callbacks,
        array $params,
        int|null $itelation,
        bool|null $sort,
        bool|null $desc
    ): void {
        $result = null;
        if (is_null($itelation)) {
            $result = Benchmark::codes($callbacks, $params);
        } elseif (is_null($sort) && is_null($desc)) {
            $result = Benchmark::codes($callbacks, $params, $itelation);
        } elseif (is_null($desc)) {
            $result = Benchmark::codes($callbacks, $params, $itelation, $sort);
        } elseif (!is_null($sort) && !is_null($desc)) {
            $result = Benchmark::codes($callbacks, $params, $itelation, $sort, $desc);
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
