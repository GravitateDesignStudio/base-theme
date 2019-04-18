<?php
namespace WPUtil;

abstract class Social
{
	/**
	 * Get a Facebook share link for the specified post
	 *
	 * @param integer $post_id
	 * @return string
	 */
	public static function get_facebook_share_link(int $post_id): string
	{
		$permalink = get_the_permalink($post_id);

		return 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($permalink);
	}

	/**
	 * Get a Twitter share link for the specified post
	 * Optionally include a username for the 'via' parameter
	 *
	 * @param integer $post_id
	 * @param string $twitter_username
	 * @return string
	 */
	public static function get_twitter_share_link(int $post_id, string $twitter_username = ''): string
	{
		$permalink = get_the_permalink($post_id);
		$title = get_the_title($post_id);

		$link = 'https://twitter.com/share?text='.urlencode($title).'&url='.urlencode($permalink);

		if ($twitter_username) {
			$link .= '&via='.urlencode($twitter_username);
		}

		return $link;
	}

	/**
	 * Get a Pinterest share link for the specified post
	 *
	 * @param integer $post_id
	 * @return string
	 */
	public static function get_pinterest_share_link(int $post_id): string
	{
		$permalink = get_the_permalink($post_id);
		$title = get_the_title($post_id);

		return 'https://www.pinterest.com/pin/create/button/?url='.urlencode($permalink).'&media=&description='.urlencode($title);
	}

	/**
	 * Get a LinkedIn share link for the specified post
	 *
	 * @param integer $post_id
	 * @return string
	 */
	public static function get_linkedin_share_link(int $post_id): string
	{
		$permalink = get_the_permalink($post_id);

		return 'https://www.linkedin.com/cws/share?url='.urlencode($permalink);
	}
}
