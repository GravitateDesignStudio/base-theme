<?php
namespace WPUtil;

abstract class Util
{
	/**
	 * Remove specific HTML tags from a string
	 *
	 * @param string $str
	 * @param array $tags
	 * @return string
	 */
	public static function strip_specific_tags(string $str, array $tags): string
	{
		foreach ($tags as $tag) {
			$str = preg_replace('/<'.$tag.'[^>]*>/i', '', $str);
		    $str = preg_replace('/<\/'.$tag.'>/i', '', $str);
		}

		return trim($str);
	}

	/**
	 * 'include' all files within a path
	 * optional filter function to exclude specific files
	 *
	 * @param string $path
	 * @param callable $filter_func
	 * @return void
	 */
	public static function include_all_files(string $path, callable $filter_func = null): void
	{
		$files = glob($path);

		foreach ($files as $file) {
			if (is_callable($filter_func) && !$filter_func($file)) {
				continue;
			}

			include_once $file;
		}
	}

	/**
	 * Register an 'init' hook to flush rewrite rules if the registered
	 * CPTs or taxonomies have changed
	 *
	 * @return void
	 */
	public static function autoflush_rewrite_rules(): void
	{
		add_action('init', function() {
			$cache = implode('', get_post_types()).implode('', get_taxonomies());

			if (get_option('wputil_registered_cpts_and_tax') !== $cache) {
				flush_rewrite_rules();
				update_option('wputil_registered_cpts_and_tax', $cache);
			}
		});
	}

	/**
	 * Convert a key/value array to a string of HTML attribute values
	 *
	 * @param array<string, mixed> $attr_array
	 * @return string
	 */
	public static function attributes_array_to_string(array $attr_array): string
	{
		$parts = [];

		foreach ($attr_array as $key => $value) {
			$parts[] = sprintf('%s="%s"', $key, esc_attr($value));
		}

		return implode(' ', $parts);
	}
}
