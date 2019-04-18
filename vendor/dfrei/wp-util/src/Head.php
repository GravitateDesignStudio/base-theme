<?php
namespace WPUtil;

abstract class Head
{
	/**
	 * Remove various unneeded elements from wp_head
	 *
	 * @return void
	 */
	public static function cleanup(): void
	{
		add_action('init', function() {
			// EditURI link
			remove_action('wp_head', 'rsd_link');

			// Windows Live Writer
			remove_action('wp_head', 'wlwmanifest_link');

			// index link
			remove_action('wp_head', 'index_rel_link');

			// previous link
			remove_action('wp_head', 'parent_post_rel_link', 10, 0);

			// start link
			remove_action('wp_head', 'start_post_rel_link', 10, 0);

			// Links for Adjacent Posts
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

			// WP version
			remove_action('wp_head', 'wp_generator');
	
			if (!is_admin()) {
				wp_deregister_script('wp-embed');
			}
		});
	}

	/**
	 * Remove RSS feed links from wp_head
	 *
	 * @return void
	 */
	public static function remove_rss_feed_links(): void
	{
		add_action('init', function() {
			// Category Feeds
			remove_action('wp_head', 'feed_links_extra', 3);

			// Post and Comment Feeds
			remove_action('wp_head', 'feed_links', 2);
		});
	}
}
