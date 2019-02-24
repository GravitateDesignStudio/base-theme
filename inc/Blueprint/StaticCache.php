<?php
namespace Blueprint;

abstract class StaticCache
{
	protected static $cache = [];

	public static function get($key)
	{
		return isset(self::$cache[$key]) ? self::$cache[$key] : null;
	}

	public static function set($key, $value)
	{
		self::$cache[$key] = $value;
	}
}
