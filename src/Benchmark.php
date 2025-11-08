<?php

namespace Macocci7\PhpBenchmark;

/**
 * class for simple benchmark.
 *
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Benchmark
{
    /**
     * runs the callback and returns the total and average by microseconds.
     *
     * @param   \Closure    $callback
     * @param   int         $iteration = 1
     * @return  array{time: float, average: float}
     */
    public static function run(
        \Closure $callback,
        ?int $iteration = 1
    ): array {
        $start = microtime(true);
        for ($i = 0; $i < $iteration; $i++) {
            $callback();
        }
        $time = microtime(true) - $start;
        $average = $iteration > 0 ? $time / $iteration : $time;
        return ['time' => $time, 'average' => $average];
    }

    /**
     * Returns the result as an array.
     *
     * @param   \Closure[] $callbacks
     * @param   int         $iteration = 1
     * @return  array<string, array{time: float, average: float}>
     */
    public static function getResults(
        array $callbacks,
        int $iteration = 1
    ): array {
        $results = [];
        foreach ($callbacks as $name => $callback) {
            $results[$name] = static::run($callback, $iteration);
        }

        return $results;
    }

    /**
     * benchmarks single code.
     *
     * @param   string      $name
     * @param   \Closure    $callback
     * @param   int         $iteration = 1
     */
    public static function code(
        string $name,
        \Closure $callback,
        int $iteration = 1
    ): void {
        $result = static::getResults([$name => $callback], $iteration);
        static::stdout($result);
    }

    /**
     * benchmarks multiple codes.
     *
     * @param   \Closure[]  $callbacks
     * @param   int         $iteration = 1
     * @param   string      $sortOrder = ''
     * @thrown  \Exception
     */
    public static function codes(
        array $callbacks,
        int $iteration = 1,
        string $sortOrder = '',
    ): void {
        if (empty($callbacks)) {
            throw new \Exception("empty codes.");
        }

        $results = static::getResults($callbacks, $iteration);

        static::sort($results, $sortOrder);

        static::stdout($results);
    }

    /**
     * sorts results
     *
     * @param   array<string, array{time: float, average: float}>   $results
     * @param   string  $sortOrder
     */
    protected static function sort(array &$results, string $sortOrder): void
    {
        if ($sortOrder === "desc") {
            uasort($results, fn ($a, $b) => $b['time'] <=> $a['time']);
        } elseif ($sortOrder === "asc") {
            uasort($results, fn ($a, $b) => $a['time'] <=> $b['time']);
        }
    }

    /**
     * outputs the results to STDOUT.
     *
     * @param   array<string, array{time: float, average: float}>   $results
     */
    public static function stdout(array $results): void
    {
        $i = 0;
        $lnumber = strlen((string) count($results));
        $lengths = array_map(
            fn ($name) => strlen($name),
            array_keys($results)
        );
        $lname = max(count($lengths) > 0 ? $lengths : [1]);
        $format = "%" . $lnumber
                . "s: %" . $lname
                . "s => Time: %.6f sec  "
                . "Avg: %.10f sec";
        foreach ($results as $name => $result) {
            $i++;
            echo sprintf(
                $format,
                $i,
                $name,
                $result['time'],
                $result['average']
            ) . PHP_EOL;
        }
    }
}
