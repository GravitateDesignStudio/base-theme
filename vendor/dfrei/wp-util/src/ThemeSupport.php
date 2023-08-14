<?php
namespace WPUtil;

abstract class ThemeSupport
{
	static public $support_items = array();
    static private $hook_registered = false;

	/**
	 * Add an 'add_theme_support' item to be run in the 'after_setup_theme' hook
	 *
	 * @param string $name
	 * @param mixed $params
	 * @return void
	 */
	static public function add(string $name, $params = ''): void
	{
		self::$support_items[$name] = $params;

        if (!self::$hook_registered) {
            add_action('after_setup_theme', function() {
				foreach (self::$support_items as $name => $params) {
                    if ($params) {
                        add_theme_support($name, $params);
                    } else {
                        add_theme_support($name);
                    }
                }
            });

            self::$hook_registered = true;
        }
    }

	/**
	 * Configure the post thumbnail dimensions
	 *
	 * @param integer $width
	 * @param integer $height
	 * @param boolean $crop
	 * @return void
	 */
	public static function post_thumbnails(int $width, int $height, bool $crop = true): void
	{
		add_action('after_setup_theme', function() use (&$width, &$height, &$crop) {
			add_theme_support('post-thumbnails');
			set_post_thumbnail_size($width, $height, $crop);
		});
	}

	/**
	 * Set image sizes
	 * $sizes must be a key/value array with each item being an array containing the following keys
	 *     'width' (int)
	 *     'height' (int)
	 *     'crop' (bool)
	 *
	 * @param array $sizes
	 * @return void
	 */
	public static function image_sizes(array $sizes): void
	{
		if (!is_array($sizes)) {
			return;
		}

		add_action('after_setup_theme', function() use (&$sizes) {
			foreach ($sizes as $key => $values) {
				if (!isset($values['width']) || !isset($values['height'])) {
					continue;
				}

				if (!isset($values['crop'])) {
					$values['crop'] = false;
				}

				add_image_size($key, $values['width'], $values['height'], $values['crop']);
			}
		});
	}

	/**
	 * Enable automatic feed links (RSS) in the wp_head() call
	 * Options:
	 *     'disable_comments_feed' (boolean) - will prevent the comments feed from being included
	 *
	 * @param array $opts
	 * @return void
	 */
	public static function automatic_feed_links(array $opts = []): void
	{
		add_action('after_setup_theme', function() use (&$opts) {
			add_theme_support('automatic-feed-links');

			if (isset($opts['disable_comments_feed']) && $opts['disable_comments_feed']) {
				add_filter('feed_links_show_comments_feed', '__return_false');
			}
		});
	}

	/**
	 * Add theme support for a custom logo
	 *
	 * @return void
	 */
	public static function custom_logo(): void
	{
		add_action('after_setup_theme', function() {
			add_theme_support('custom-logo');
		});
	}

	/**
	 * Register custom menus using the 'register_nav_menus' function
	 *
	 * @param array $menus
	 * @return void
	 */
	public static function register_menus(array $menus): void
	{
		add_action('after_setup_theme', function() use (&$menus) {
			add_theme_support('menus');
			register_nav_menus($menus);
		});
	}
}
