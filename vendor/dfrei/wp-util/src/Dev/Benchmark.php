<?php
namespace WPUtil\Dev;

abstract class Benchmark
{
	protected static $timings = [];

	/**
	 * Benchmark the execution time of a function
	 *
	 * @param callable $func
	 * @param mixed ...$params
	 * @return float
	 */
	public static function get_microtime(callable $func, ...$params): float
	{
		$time_start = microtime(true);

		$func(...$params);

		$time_end = microtime(true);

		return (float)$time_end - (float)$time_start;
	}

	/**
	 * Start a benchmark timing
	 *
	 * @param string $timing_id
	 * @return void
	 */
	public static function start(string $timing_id): void
	{
		self::$timings[$timing_id] = ['start' => (float)microtime(true), 'stop' => ''];
	}

	/**
	 * End a benchmark timing
	 *
	 * @param string $timing_id
	 * @param boolean $to_error_log
	 * @return float
	 */
	public static function stop(string $timing_id, bool $to_error_log = true): float
	{
		if (!isset(self::$timings[$timing_id])) {
			return false;
		}

		self::$timings[$timing_id]['stop'] = (float)microtime(true);

		$total_time = self::$timings[$timing_id]['stop'] - self::$timings[$timing_id]['start'];

		if ($to_error_log) {
			error_log("{$timing_id}: {$total_time}");
		}

		return $total_time;
	}
}
