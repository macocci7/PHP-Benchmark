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
                static::mbSprintfPad($name, $lname, " ", "right"),
                $result['time'],
                $result['average']
            ) . PHP_EOL;
        }
    }

    /**
     * Returns the analyzed results.
     * @param   \Closure[]  $callbacks
     * @param   int         $iteration = 1
     * @return  array{
     *   fastest: array<string, array{time: float, average: float}>,
     *   slowest: array<string, array{time: float, average: float}>,
     *   details: array<string, array{
     *     time: float,
     *     average: float,
     *     relative_to_fastest: float|null,
     *     relative_to_slowest: float|null
     *   }>
     * }
     */
    public static function getAnalyzedResults(
        array $callbacks,
        int $iteration = 1,
    ): array {
        $analyzed = [];
        $measured = static::getResults($callbacks, $iteration);

        $min = null;
        $max = null;
        $keyMin = null;
        $keyMax = null;
        foreach ($measured as $key => $result) {
            if (is_null($min) || $result['time'] < $min) {
                $min = $result['time'];
                $keyMin = $key;
            }
            if (is_null($max) || $result['time'] > $max) {
                $max = $result['time'];
                $keyMax = $key;
            }
        }
        $analyzed["fastest"] = [$keyMin => $measured[$keyMin]];
        $analyzed["slowest"] = [$keyMax => $measured[$keyMax]];
        $analyzed["details"] = [];

        foreach ($measured as $name => $result) {
            $analyzed["details"][$name] = [
                'time' => $result['time'],
                'average' => $result['average'],
                'relative_to_fastest' => $min > 0 ? $result['time'] / $min : null,
                'relative_to_slowest' => $max > 0 ? $result['time'] / $max : null,
            ];
        }
        return $analyzed;
    }

    /**
     * benchmarks multiple codes and display analyzed results.
     *
     * @param   \Closure[]  $callbacks
     * @param   int         $iteration = 1
     * @param   string      $sortOrder = ''
     * @thrown  \Exception
     */
    public static function analyze(
        array $callbacks,
        int $iteration = 1,
        string $sortOrder = '',
    ): void {
        $results = static::getAnalyzedResults($callbacks, $iteration);

        $i = 0;
        $lnumber = strlen((string) count($results["details"]));
        $lengths = array_map(
            fn ($name) => mb_strwidth($name),
            array_keys($results["details"])
        );
        $lname = max(count($lengths) > 0 ? $lengths : [1]);
        echo "Analyzed Results:" . PHP_EOL;
        echo "- Fastest: "
            . key($results["fastest"]) . " ("
            . sprintf("%.6f sec", current($results["fastest"])['time']) . ")"   // @phpstan-ignore-line
            . PHP_EOL;
        echo "- Slowest: "
            . key($results["slowest"]) . " ("
            . sprintf("%.6f sec", current($results["slowest"])['time']) . ")"   // @phpstan-ignore-line
            . PHP_EOL;
        echo "- Details:" . PHP_EOL;
        $format = "  %" . $lnumber . "s  "
                . "%" . ($lname - 2) . "s    "
                . "    R2F   "
                . "    R2S   "
                . "Time          "
                . "Average   "
                ;
        echo sprintf($format, "No.", "Name") . PHP_EOL;
        $hl = "  " . str_repeat("-", $lnumber)
            . "+" . str_repeat("-", $lname + 2)
            . "+" . str_repeat("-", 10)
            . "+" . str_repeat("-", 9)
            . "+" . str_repeat("-", 13)
            . "+" . str_repeat("-", 17)
            ;
        echo $hl . PHP_EOL;

        $format = "  %" . $lnumber . "s: "
                . "%" . $lname . "s => "
                . "    %0.2f  "
                . "    %0.2f  "
                . "%.6f sec  "
                . "%.10f sec  "
                ;
        echo "format:" . $format . PHP_EOL; // debug line
        foreach ($results["details"] as $name => $result) {
            $i++;
            echo sprintf(
                $format,
                $i,
                static::mbSprintfPad($name, $lname, " ", "right"),
                $result['relative_to_fastest'],
                $result['relative_to_slowest'],
                $result['time'],
                $result['average']
            ) . PHP_EOL;
        }
    }

    /**
     * pads for multibyte strings.
     *
     * @param   string  $text
     * @param   int     $width
     * @param   string  $padChar = ' '
     * @param   string  $align = 'right'  options: 'right', 'left', 'center'
     */
    protected static function mbSprintfPad(
        string $text,
        int $width,
        string $padChar = " ",
        string $align = "right"
    ): string {
        $textWidth = mb_strwidth($text, "UTF-8");
        $padLen = max($width - $textWidth, 0);

        if ($align === "right") {
            return str_repeat($padChar, $padLen) . $text;
        } elseif ($align === "left") {
            return $text . str_repeat($padChar, $padLen);
        } else { // center
            $left = (int) floor($padLen / 2);
            $right = $padLen - $left;
            return str_repeat($padChar, $left) . $text . str_repeat($padChar, $right);
        }
    }
}
