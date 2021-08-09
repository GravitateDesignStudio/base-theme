<?php
namespace WPUtil;

abstract class StaticCache
{
	protected static $cache = [];

	/**
	 * Get a value stored in the static cache by name. Returns null if the value
	 * does not exist
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function get(string $key)
	{
		return isset(self::$cache[$key]) ? self::$cache[$key] : null;
	}

	/**
	 * Set a value stored in the static cache by name.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public static function set(string $key, $value)
	{
		self::$cache[$key] = $value;
	}
}
