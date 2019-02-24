<?php
namespace Blueprint;

abstract class Util
{
	public static function get_post_content($post_id)
	{
		$post = get_post($post_id);

		return $post ? $post->post_content : '';
	}

	public static function attributes_array_to_string($attr_array)
	{
		$parts = [];

		foreach ($attr_array as $key => $value) {
			$parts[] = sprintf('%s="%s"', $key, esc_attr($value));
		}

		return implode(' ', $parts);
	}
}
