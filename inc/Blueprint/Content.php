<?php
namespace Blueprint;

abstract class Content
{
	/**
	 * Get the excerpt for a post with various options
	 *
	 * Options:
	 *     'force_blocks' (bool) - force Gravitate Blocks content to be used (default: false)
	 *     'force_default' (bool) - force default post content to be used (default: false)
	 *     'full_content' (bool) - return the full content (default: false)
	 *     'wpautop' (bool) - apply 'wpautop' to the content (default: false)
	 *     'strip_tags' (bool) - strip HTML tags on the content (default: true)
	 *     'strip_shortcodes' (bool) - strip shortcodes from the content (default: true)
	 *     'prefer_excerpt' (bool) - prioritize the 'excerpt' field (default: false)
	 *     'length' (int) - control the length of the excerpt (default: 55)
	 *     'more' (string) - customize the 'read more' text (default: '&hellip;')
	 *     'filter_nbsp' (bool) - filter '&nbsp;' sequences from the content (default: true)
	 *     'meta_key_match' (array) - 'meta_key' fields to match for Gravitate Blocks content
	 *         (default: ['grav\_blocks\__\_content', 'grav\_blocks\__\_content\_column\__\_column', 'grav\_blocks\__\_column\__'])
	 *     'post_id' (int) - manually specify the post id (default is current post)
	 *
	 * @param array $opts
	 * @return string
	 */
	public static function get_excerpt(array $opts = []): string
	{
		// options and variables
		$force_blocks = isset($opts['force_blocks']) ? $opts['force_blocks'] : false;
		$force_default = isset($opts['force_default']) ? $opts['force_default'] : false;
		$full_content = isset($opts['full_content']) ? $opts['full_content'] : false;
		$autop = isset($opts['wpautop']) ? $opts['wpautop'] : false;
		$strip_tags = isset($opts['strip_tags']) ? $opts['strip_tags'] : true;
		$strip_shortcodes = isset($opts['strip_shortcodes']) ? $opts['strip_shortcodes'] : true;
		$prefer_excerpt = isset($opts['prefer_excerpt']) ? $opts['prefer_excerpt'] : false;
		$excerpt_length = isset($opts['length']) ? $opts['length'] : apply_filters('excerpt_length', 55);
		$excerpt_more = isset($opts['more']) ? $opts['more'] : apply_filters('excerpt_more', ' [&hellip;]');
		$filter_nbsp = isset($opts['filter_nbsp']) ? $opts['filter_nbsp'] : true;
		$meta_key_match = (isset($opts['meta_key_match']) && is_array($opts['meta_key_match'])) ? $opts['meta_key_match'] : array('grav\_blocks\__\_content', 'grav\_blocks\__\_content\_column\__\_column', 'grav\_blocks\__\_column\__');
		$post_id = isset($opts['post_id']) ? (int)$opts['post_id'] : get_the_ID();
		$post = get_post($post_id);


		if ($prefer_excerpt && trim($post->post_excerpt)) {
			if ($autop) {
				return wpautop($post->post_excerpt);
			}

			return $post->post_excerpt;
		}

		$content = $post->post_content;

		// get the content from blocks
		if ((empty($content) || $force_blocks) && !$force_default) {
			$meta = get_metadata('post', $post_id);

			if (isset($meta['grav_blocks']) && is_array($meta['grav_blocks']) && $meta['grav_blocks'][0]) {
				global $wpdb;

				// prepare our sql query
				$meta_key_match = array_map(function ($match_str) use (&$wpdb) {
					return $wpdb->prepare('meta_key LIKE %s', $match_str);
				}, $meta_key_match);

				$meta_key_match_str = implode(' OR ', $meta_key_match);

				$sql = "SELECT meta_value FROM {$wpdb->postmeta} WHERE ({$meta_key_match_str}) AND post_id = {$post_id} LIMIT 10";

				// fetch our results
				// phpcs:ignore
				$results = $wpdb->get_results($sql, ARRAY_N);

				if ($results) {
					// flatten our multidimensional array and
					// concatenate it to usable content
					$content_parts = array();

					foreach ($results as $result) {
						$content_parts = array_merge($content_parts, $result);
					}

					$content = implode(' ', $content_parts);
				} else {
					// default to empty content
					$content = '';
				}
			}
		}

		// option to strip tags
		if ($strip_tags) {
			$content = wp_strip_all_tags($content);
		}

		// option to strip shortcodes
		if ($strip_shortcodes) {
			$content = strip_shortcodes($content);
		}

		// escape html
		$content = str_replace([']]>', '\]\]\>'], ']]&gt;', $content);

		// escape scripts
		$content = preg_replace('@<script[^>]*?>.*?</script>@si', '', $content);

		// handle as excerpt if not full content
		if (!$full_content) {
			$content = apply_filters('the_excerpt', $content);
			$content = wp_trim_words($content, $excerpt_length, $excerpt_more);
		}

		// option to wpautop
		if ($autop) {
			$content = wpautop($content);
		}

		if ($filter_nbsp) {
			$content = str_replace('&nbsp;', '', $content);
		}

		return trim($content);
	}
}
