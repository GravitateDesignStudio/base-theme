<?php
namespace Blueprint;

abstract class SocialShare
{
	/**
	 * Replace values in a template URL based on the specified platform.
	 * Returns a URL used for sharing on social media platforms.
	 *
	 * @param string $platform
	 * @param integer $post_id
	 * @return string
	 */
	public static function get_social_share_link(string $platform, int $post_id = 0): string
	{
		// use the post id if none was supplied
		if (!$post_id) {
			$post_id = get_the_ID();
		}

		// return an empty string if there is no post id
		if (!$post_id) {
			return '';
		}

		// get the URL template based on the specified platform
		$platform = strtolower($platform);
		$url_template = get_field('theme_options_social_'.$platform.'_share_url', 'option');

		// return if the template does not exist
		if (!$url_template) {
			return '';
		}

		// replace "[post_link]" placeholder
		$url_template = str_replace('[post_link]', urlencode(get_the_permalink($post_id)), $url_template);

		// replace "[post_title]" placeholder
		$url_template = str_replace('[post_title]', urlencode(get_the_title($post_id)), $url_template);

		// if twitter, replace "[twitter_username]" placeholder
		if ($platform === 'twitter') {
			$twitter_username = get_field('theme_options_social_twitter_username', 'option');

			$url_template = str_replace('[twitter_username]', $twitter_username ? urlencode('@'.$twitter_username) : '', $url_template);
		}

		return $url_template;
	}
}
