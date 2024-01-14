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
     * @param   array       $params
     * @param   int         $iteration = 1
     * @return  float
     */
    public static function run( // @phpstan-ignore-line
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
     * @param   array       $params
     * @param   int         $iteration = 1
     * @return  float[]
     */
    public static function code( // @phpstan-ignore-line
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
     * @param   mixed[]     $callbacks
     * @param   array       $params
     * @param   int         $iteration = 1
     * @param   bool        $sort = false
     * @param   bool        $desc = false
     * @return  float[]
     * @thrown  \Exception
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public static function codes( // @phpstan-ignore-line
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
            $results[$name] = self::run($callback, $params, $iteration); // @phpstan-ignore-line
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
        $lname = max(
            array_map(
                fn ($name) => strlen($name),
                array_keys($results)
            )
        );
        $format = "%" . $lnumber . "d: %" . $lname . "s =>\tTime: %.6f sec\n";
        foreach ($results as $name => $time) {
            $i++;
            echo sprintf($format, $i, $name, $time);
        }
    }
}
