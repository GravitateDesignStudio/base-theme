<?php
namespace Blueprint;

abstract class Blocks
{
	protected static $bg_colors;

	/**
	 * Enforce background color choices
	 *
	 * @param array $new_colors Key (class name) / Value (color name) pair
	 * @return void
	 */
	public static function enforce_background_colors(array $new_colors): void
	{
		if (!is_array($new_colors)) {
			return;
		}

		self::$bg_colors = $new_colors;

		add_filter('grav_block_background_colors', function($colors) use (&$new_colors) {
			return $new_colors;
		});
	}

	public static function get_bg_colors(array $opts = []): array
	{
		if (!self::$bg_colors || !is_array(self::$bg_colors)) {
			return [];
		}

		$colors = [];

		return array_filter(self::$bg_colors, function($name, $class) use (&$opts) {
			if (isset($opts['exclude']) && is_array($opts['exclude']) && in_array($class, $opts['exclude'])) {
				return false;
			}
			
			return true;
		}, ARRAY_FILTER_USE_BOTH);

		return $colors;
	}

	/**
	 * Sort the order blocks appear in the flexible field dropdown list
	 * alphabetically
	 */
	public static function sort_block_names_alphabetically(): void
	{
		add_filter('grav_block_fields', function ($layouts) {
			uasort($layouts, function($a, $b) {
				return strcasecmp($a['label'], $b['label']);
			});
		
			return $layouts;
		}, 1000);
	}

	/**
	 * Ensure the GRAV_BLOCKS::get_link_fields method can be called
	 * and return the resulting 'grav_link_fields' key
	 *
	 * @param array $params
	 * @return array
	 */
	public static function safe_get_link_fields(array $params): array
	{
		if (!class_exists('GRAV_BLOCKS')) {
			return [];
		}

		$fields = \GRAV_BLOCKS::get_link_fields($params);

		if (!is_array($fields) || !isset($fields['grav_link_fields'])) {
			return [];
		}

		return $fields['grav_link_fields'];
	}
}
