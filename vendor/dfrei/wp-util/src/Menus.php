<?php
namespace WPUtil;

abstract class Menus
{
	/**
	 * Return a key/value list of currently registered menus
	 * Key will be the menu id, value will be the menu name
	 *
	 * @return array
	 */
	public static function get_menus(): array
	{
		$menus = [];

		foreach (get_terms('nav_menu') as $menu) {
			$menus[$menu->term_id] = $menu->name;
		}

		return $menus;
	}

	/**
	 * Wrapper for 'get_registered_nav_menus'
	 *
	 * @return array
	 */
	public static function get_locations(): array
	{
		return get_registered_nav_menus();
	}

	/**
	 * Call 'wp_nav_menu' for a specific location
	 *
	 * @param string $theme_location
	 * @param array $opts
	 * @return void
	 */
	public static function display_for_location(string $theme_location, $opts = []): void
	{
		$menu_opts = array_merge([
			'theme_location' => $theme_location,
			'container' => ''
		], $opts);

		wp_nav_menu($menu_opts);
	}

	/**
	 * Return the output of 'wp_get_nav_menu_items' for a specific location
	 *
	 * @param string $theme_location The menu location name
	 * @param array $opts An array of options to pass to wp_get_nav_menu_items
	 * @param int $menu_item_parent Return only menu items with a specific menu_item_parent value
	 * @return array
	 */
	public static function get_for_location(string $theme_location, $opts = [], $menu_item_parent = -1): array
	{
		$locations = get_nav_menu_locations();

		if (!isset($locations[$theme_location])) {
			return [];
		}

		$object = wp_get_nav_menu_object($locations[$theme_location]);

		if ($object === false) {
			return [];
		}

		$menu_items = wp_get_nav_menu_items($object->name, $opts);

		if (!is_array($menu_items)) {
			return [];
		}

		if ($menu_item_parent >= 0) {
			$menu_items = array_filter($menu_items, function ($item) use (&$menu_item_parent) {
				return (int)$item->menu_item_parent === $menu_item_parent;
			});
		}

		return $menu_items;
	}
}
