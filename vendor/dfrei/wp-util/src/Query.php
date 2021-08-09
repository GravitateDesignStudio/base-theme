<?php
namespace WPUtil;

use WP_Query;

abstract class Query
{
	/**
	 * Modifies a posts query object value. Can accept either or a WP_Query object
	 * (used in "pre_get_posts") or an array (used in "rest_post_query")
	 *
	 * @param \WP_Query|array $query
	 * @param string $name
	 * @param mixed $value
	 * @return \WP_Query|array
	 */
	public static function setQueryValue($query, string $name, $value)
	{
		if ($query instanceof WP_Query) {
			$query->set($name, $value);
		} else if (is_array($query)) {
			$query[$name] = $value;
		}

		return $query;
	}

	/**
	 * Retrieve a value from a posts query object. Can accept either or a WP_Query object
	 * (used in "pre_get_posts") or an array (used in "rest_post_query")
	 *
	 * @param \WP_Query|array $query
	 * @param string $name
	 * @param mixed $default
	 * @return \WP_Query|array
	 */
	public static function getQueryValue($query, string $name, $default = null)
	{
		return $query instanceof WP_Query ?
			$query->get($name, $default) :
			$query[$name] ?? $default;
	}
}
