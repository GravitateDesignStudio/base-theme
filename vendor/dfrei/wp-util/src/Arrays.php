<?php
namespace WPUtil;

abstract class Arrays
{
	/**
	 * Return the value of an array key as a string with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param string|callable $default
	 * @param boolean $trim
	 * @return string
	 */
	public static function get_value_as_string(array $values, string $key, $default = '', bool $trim = true): string
	{
		if (isset($values[$key]) && is_string($values[$key])) {
			return $trim ? trim($values[$key]) : $values[$key];
		}

		// NOTE: Check if the default value is a string and return it before checking
		// if it is callable. If the string value is the name of an internal PHP
		// function (ex: 'printf'), then is_callable will return true and cause an
		// exception to be thrown.
		if (is_string($default)) {
			return $default;
		}

		return is_callable($default) ? $default() : $default;
	}

	/**
	 * Return the value of an array key as an array with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param array|callable $default
	 * @return array
	 */
	public static function get_value_as_array(array $values, string $key, $default = []): array
	{
		return (isset($values[$key]) && is_array($values[$key])) ? $values[$key] : (is_callable($default) ? $default() : $default);
	}

	/**
	 * Return the value of an array key as a bool with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param boolean|callable $default
	 * @return boolean
	 */
	public static function get_value_as_bool(array $values, string $key, $default = false): bool
	{
		if (!isset($values[$key])) {
			return is_callable($default) ? $default() : $default;
		}

		return is_bool($values[$key]) ? $values[$key] : intval($values[$key]) === 1;
	}

	/**
	 * Return the value of an array key as an int with an optional default value
	 *
	 * @param array $values
	 * @param string $key
	 * @param integer|callable $default
	 * @return integer
	 */
	public static function get_value_as_int(array $values, string $key, $default = 0): int
	{
		if (!isset($values[$key])) {
			return is_callable($default) ? $default() : $default;
		}

		return intval($values[$key]);
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
