<?php
namespace WPUtil;

abstract class Templates
{
	/**
	 * Set a path to load template files from instead of the theme root
	 *
	 * @param string $path
	 * @return void
	 */
	public static function use_path_for_templates(string $path, bool $merge_existing = false): void
	{
		$types = [
			'index',
			'404',
			'archive',
			'author',
			'category',
			'tag',
			'taxonomy',
			'date',
			'embed',
			'home',
			'frontpage',
			'front_page',
			'page',
			'paged',
			'search',
			'single',
			'singular',
			'attachment'
		];

		foreach ($types as $type) {
			add_filter("{$type}_template_hierarchy", function($templates) use ($path, $merge_existing) {
				$templates_custom_path = array_map(function($filename) use ($path) {
					$new_path = trailingslashit($path);

					// leave the filename untouched if the new path 
					// is found at the beginning of the string
					if (strpos($filename, $new_path) === 0) {
						return $filename;
					}

					return trailingslashit($path).$filename;
				}, $templates ?? []);

				return $merge_existing ? array_merge($templates_custom_path, $templates) : $templates_custom_path;
			});
		}
	}
}
