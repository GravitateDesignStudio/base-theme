<?php
namespace WPUtil\Vendor;

use WPUtil\Models\BlueprintBlocksLink;
use WPUtil\StaticCache;

abstract class BlueprintBlocks
{
	protected static $bg_colors;

	/**
	 * Enforce background color choices
	 *
	 * @param array $new_colors Key (class name) / Value (color name) pair.
	 * @return void
	 */
	public static function enforce_background_colors(array $new_colors): void
	{
		if (!is_array($new_colors)) {
			return;
		}

		self::$bg_colors = $new_colors;

		add_filter('grav_block_background_colors', function ($colors) use (&$new_colors) {
			return $new_colors;
		});
	}

	public static function get_bg_colors(array $opts = []): array
	{
		if (!self::$bg_colors || !is_array(self::$bg_colors)) {
			return [];
		}

		$colors = array_filter(self::$bg_colors, function ($name, $class) use (&$opts) {
			if (isset($opts['exclude']) && is_array($opts['exclude']) && in_array($class, $opts['exclude'], true)) {
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
			uasort($layouts, function ($a, $b) {
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

	/**
	 * Ensure the GRAV_BLOCKS::display method can be called
	 *
	 * @param array $params
	 * @return void
	 */
	public static function safe_display(array $params = []): void
	{
		if (!class_exists('GRAV_BLOCKS')) {
			return;
		}

		\GRAV_BLOCKS::display($params);
	}

	/**
	 * Get the values for a button field created with the 'GRAV_BLOCKS::get_link_fields' method.
	 * Returns an object with 'text', 'link', and 'style' properties.
	 *
	 * @param string $acf_field
	 * @param int|array $object Can be a post ID or an array. Defaults to the current post ID.
	 * @return \WPUtil\Models\BlueprintBlocksLink
	 */
	public static function get_button_field_values(string $acf_field, $object = 0): BlueprintBlocksLink
	{
		$button_values = new BlueprintBlocksLink();

		if (is_int($object)) {
			$post_id = $object ? $object : get_the_ID();
			$button_type = get_field($acf_field . '_type', $post_id) ?? 'none';

			if ($button_type !== 'none') {
				$button_values->type = $button_type;
				$button_values->text = get_field($acf_field . '_text', $post_id) ?? '';
				$button_values->link = get_field($acf_field . '_' . $button_type, $post_id) ?? '';
				$button_values->style = get_field($acf_field . '_style', $post_id) ?? '';
			}

		} else if (is_array($object)) {
			$button_type = $object[$acf_field . '_type'] ?? 'none';

			if ($button_type !== 'none') {
				$button_values->type = $button_type;
				$button_values->text = $object[$acf_field . '_text'] ?? '';
				$button_values->link = $object[$acf_field . '_' . $button_type] ?? '';
				$button_values->style = $object[$acf_field . '_style'] ?? '';
			}
		}

		return $button_values;
	}

	/**
	 * Specify which blocks should be allowed to use the animate option
	 *
	 * @param array<string> $allow_block_names Array of block names
	 * @return void
	 */
	public static function allow_animate_option_for_blocks(array $allow_block_names)
	{
		add_filter('grav_block_fields', function ($fields) use ($allow_block_names) {
			$block_names = array_keys($fields);

			foreach ($block_names as $block_name) {
				if (in_array($block_name, $allow_block_names, true) || !isset($fields[$block_name]['sub_fields'])) {
					continue;
				}

				$fields[$block_name]['sub_fields'] = array_filter($fields[$block_name]['sub_fields'], function ($sub_field) {
					$name = $sub_field['name'] ?? '';

					return $name !== 'block_animate';
				});
			}

			return $fields;
		});
	}

	/**
	 * Hide unused default blocks from the blocks settings page
	 *
	 * @param array<string> $unused_blocks
	 * @return void
	 */
	public static function hide_unused_blocks(array $unused_blocks)
	{
		add_filter('grav_blocks', function ($blocks) use ($unused_blocks) {
			$available_blocks = array_filter($blocks, function ($values, $key) use ($unused_blocks) {
				return $values['group'] !== 'default' || !in_array($key, $unused_blocks, true);
			}, ARRAY_FILTER_USE_BOTH);

			return $available_blocks;
		});
	}

	/**
	 * Return an array of block names for blocks included within the theme directory
	 *
	 * @return array<string>
	 */
	public static function get_theme_blocks_list(): array
	{
		$theme_blocks = StaticCache::get('blueprint_blocks_theme_blocks');

		if (!is_array($theme_blocks)) {
			$theme_blocks_dir = get_template_directory() . '/grav-blocks/';

			if (is_dir($theme_blocks_dir)) {
				$theme_blocks = glob($theme_blocks_dir . '*', GLOB_ONLYDIR);
				$theme_blocks = array_map(fn ($block_path) => basename($block_path), $theme_blocks);

				StaticCache::set('blueprint_blocks_theme_blocks', $theme_blocks);
			}
		}

		return $theme_blocks;
	}

	/**
	 * Restrict the specified blocks to only allow the use of specified background
	 * colors. This method takes an array where the keys are block names and the
	 * values are arrays with the names of allowed background colors.
	 *
	 * Ex:
	 * [
	 *     'calltoaction' => ['block-bg-none', 'bg-blue'],
	 *     'columns' => ['block-bg-none', 'bg-gray']
	 * ]
	 *
	 * @param array<string, array<string>> $blocks
	 * @return void
	 */
	public static function restrict_backgrounds_for_blocks(array $blocks)
	{
		add_filter('grav_block_background_colors', function ($colors, $block) use (&$blocks) {
			if (!isset($blocks[$block])) {
				return $colors;
			}

			$allowed_colors = $blocks[$block];

			return array_filter($colors, function ($color) use (&$allowed_colors) {
				return in_array($color, $allowed_colors, true);
			}, ARRAY_FILTER_USE_KEY);
		}, 11, 2);
	}
}
