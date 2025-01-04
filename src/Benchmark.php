<?php

namespace Macocci7\PhpBenchmark;

/**
 * class for simple benchmark.
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Benchmark
{
    /**
     * runs callback and returns microtime as seconds.
     * @param   \Closure    $callback
     * @param   mixed[]     $params
     * @param   int         $iteration = 1
     * @return  float
     */
    public static function run(
        \Closure $callback,
        array $params,
        ?int $iteration = 1
    ) {
        $start = microtime(true);
        for ($i = 0; $i < $iteration; $i++) {
            $callback(...$params);
        }
        return microtime(true) - $start;
    }

    /**
     * benchmarks the code.
     * @param   string      $name
     * @param   \Closure    $callback
     * @param   mixed[]     $params
     * @param   int         $iteration = 1
     * @return  float[]
     */
    public static function code(
        string $name,
        \Closure $callback,
        array $params,
        ?int $iteration = 1
    ) {
        $result = self::run($callback, $params, $iteration);
        return [$name => $result];
    }

    /**
     * benchmarks the codes.
     * @param   array<int, \Closure>    $callbacks
     * @param   mixed[]                 $params
     * @param   int                     $iteration = 1
     * @param   bool                    $sort = false
     * @param   bool                    $desc = false
     * @return  float[]
     * @thrown  \Exception
     */
    public static function codes(
        array $callbacks,
        array $params,
        ?int $iteration = 1,
        ?bool $sort = false,
        ?bool $desc = false,
    ) {
        if (empty($callbacks)) {
            throw new \Exception("empty codes.");
        }
        $results = [];
        foreach ($callbacks as $name => $callback) {
            $results[$name] = self::run($callback, $params, $iteration);
        }
        if ($sort) {
            if ($desc) {
                arsort($results);
            } else {
                asort($results);
            }
        }
        return $results;
    }

    /**
     * shows the results in STDOUT.
     * @param   float[]     $results
     * @return  void
     */
    public static function stdout(array $results)
    {
        $i = 0;
        $lnumber = strlen((string) count($results));
        $lengths = array_map(
            fn ($name) => strlen($name),
            array_keys($results)
        );
        $lname = max(count($lengths) > 0 ? $lengths : [1]);
        $format = "%" . $lnumber . "d: %" . $lname . "s =>\tTime: %.6f sec\n";
        foreach ($results as $name => $time) {
            $i++;
            echo sprintf($format, $i, $name, $time);
        }
    }
}
