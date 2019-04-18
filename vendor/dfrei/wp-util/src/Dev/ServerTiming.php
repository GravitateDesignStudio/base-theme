<?php
namespace WPUtil\Dev;

abstract class ServerTiming
{
	static private $proc = [];

	/**
	 * Add a 'Server-Timing' header that includes any created timings
	 *
	 * @return void
	 */
	static public function init(): void
	{
		$procs = &self::$proc;

		add_action('send_headers', function() use (&$procs) {
			$items = [];

			foreach ($procs as $key => $values) {
				$display_name = isset($values['title']) ? $values['title'] : $key;
				
				if (!$values['time_total']) {
					$values['time_total'] = microtime(true) - self::$proc[$key]['time_start'];
					$display_name = '! '.$display_name;
				}

				$items[] = "{$key}={$values['time_total']}; \"{$display_name}\" ";
			}

			header('Server-Timing: '.implode(', ', $items));
		}, 9999);
	}

	/**
	 * Start a timing
	 *
	 * @param string $name
	 * @param string $title
	 * @return void
	 */
	static public function start(string $name, string $title = ''): void
	{
		$key = sanitize_title($name);

		if (isset(self::$proc[$key])) {
			return;
		}

		self::$proc[$key] = [
			'time_start' => microtime(true),
			'time_total' => 0,
			'title' => $title
		];
	}

	/**
	 * End a timing
	 *
	 * @param string $name
	 * @return float
	 */
	static public function stop(string $name): float
	{
		$key = sanitize_title($name);

		if (!isset(self::$proc[$key])) {
			return;
		}

		self::$proc[$key]['time_total'] = microtime(true) - self::$proc[$key]['time_start'];

		return self::$proc[$key]['time_total'];
	}
}
