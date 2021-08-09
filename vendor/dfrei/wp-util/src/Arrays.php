<?php
namespace WPUtil;

abstract class Arrays
{
	/**
	 * Return the value of an array key as a string with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param string $default
	 * @param boolean $trim
	 * @return string
	 */
	public static function get_value_as_string(array $values, string $key, string $default = '', bool $trim = true): string
	{
		if (isset($values[$key]) && is_string($values[$key])) {
			return $trim ? trim($values[$key]) : $values[$key];
		}

		return $default;
	}

	/**
	 * Return the value of an array key as an array with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param array $default
	 * @return array
	 */
	public static function get_value_as_array(array $values, string $key, array $default = []): array
	{
		return (isset($values[$key]) && is_array($values[$key])) ? $values[$key] : $default;
	}

	/**
	 * Return the value of an array key as a bool with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param boolean $default
	 * @return boolean
	 */
	public static function get_value_as_bool(array $values, string $key, bool $default = false): bool
	{
		if (!isset($values[$key])) {
			return $default;
		}

		return is_bool($values[$key]) ? $values[$key] : intval($values[$key]) === 1;
	}

	/**
	 * Return the value of an array key as an int with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param integer $default
	 * @return integer
	 */
	public static function get_value_as_int(array $values, string $key, int $default = 0): int
	{
		return isset($values[$key]) ? intval($values[$key]) : $default;
	}

	/**
	 * Return an array of US states with the abbreviation as the key
	 * and the name as the value
	 *
	 * @return array
	 */
	public static function us_states(): array
	{
		return ArrayLists::us_states();
	}

	/**
	 * Return an array of countries with the abbreviation as the key
	 * and the name as the value
	 *
	 * @return array
	 */
	public static function countries(): array
	{
		return ArrayLists::countries();
	}

	/**
	 * Return an array of Canadian provinces with the abbreviation as the key
	 * and the name as the value
	 *
	 * @return array
	 */
	public static function canadian_provinces(): array
	{
		return ArrayLists::canadian_provinces();
	}
}
