<?php
namespace WPUtil;

use WPUtil\Arrays;

abstract class Styles
{
	/**
	 * Checks if the "conditional_callback" key of a style element is set and
	 * returns the boolean value of that property if it is callable. If the value
	 * of the key doesn't exist or is not callable, true will be returned.
	 *
	 * @param array<string, mixed> $style
	 * @return boolean
	 */
	public static function run_conditional_callback(array $style): bool
	{
		$callback = $style['conditional_callback'] ?? null;

		if (!$callback || !is_callable($callback)) {
			return true;
		}

		return boolval($callback());
	}

	/**
	 * Enqueue an array of styles
	 * Each item can have the following keys:
	 *     'version' (string) - version for the style enqueue
	 *     'url' (string) - URL of the CSS file to enqueue
	 *     'deps' (array) - List of style handles that this enqueue depends on
	 *     'preload_hook' (string) - An optional action hook name that will be used
	 *         to output a '<link rel="preload" href="..." as="style">' tag
	 *     'conditional_callback' (function) - An optional callback function that
	 *         will be used to determine if the style should be enqueued for the
	 *         current request
	 *
	 * @param array $styles
	 * @return void
	 */
	public static function enqueue_styles(array $styles): void
	{
		foreach ($styles as &$style) {
			$url = Arrays::get_value_as_string($style, 'url');
			$version = Arrays::get_value_as_string($style, 'version');
			$preload_hook = Arrays::get_value_as_string($style, 'preload_hook');

			if (!$url) {
				continue;
			}

			// calculate versions for scripts if they are local files
			if (!$version) {
				if (strpos($url, get_template_directory_uri()) !== false) {
					// If Local file then get the time of when it was modified
					$file_path = str_replace(get_template_directory_uri(), get_template_directory(), $style['url']);

					if (file_exists($file_path)) {
						$version = filemtime($file_path);

						// ensure the style object "version" key is updated for use in the "wp_enqueue_scripts" hook below
						$style['version'] = strval($version);
					}
				}
			}

			// add a preload tag if a preload hook is specified
			if ($preload_hook) {
				$preload_url = $url;

				if ($version) {
					$preload_url .= '?ver=' . $version;
				}

				add_action($preload_hook, function () use ($preload_url, $style) {
					if (self::run_conditional_callback($style)) {
						echo '<link rel="preload" href="' . esc_url($preload_url) . '" as="style">'."\n";
					}
				});
			}
		}

		// enqueue all the styles
		add_action('wp_enqueue_scripts', function() use (&$styles) {
			foreach ($styles as $name => $params) {
				$url = Arrays::get_value_as_string($params, 'url');
				$deps = Arrays::get_value_as_array($params, 'deps');
				$version = Arrays::get_value_as_string($params, 'version');

				if (!$url || !self::run_conditional_callback($params)) {
					continue;
				}

				if (!$version) {
					$version = null;
				}

				wp_enqueue_style($name, $url, $deps, $version);
			}
		});
	}
}
