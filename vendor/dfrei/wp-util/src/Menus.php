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
}
