<?php
namespace WPUtil;

abstract class Posts
{
	/**
	 * Return the raw post content for the specified post id
	 *
	 * @param integer $post_id
	 * @return string
	 */
	public static function get_raw_post_content(int $post_id): string
	{
		$post = get_post($post_id);

		return $post ? $post->post_content : '';
	}
}
