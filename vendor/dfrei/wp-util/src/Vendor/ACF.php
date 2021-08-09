<?php
namespace WPUtil\Vendor;

abstract class ACF
{
	/**
	 * Return an array of items representing the images attached to the specified
	 * attachment. Each item will have 'url', 'width', and 'height' keys.
	 *
	 * @param int|array $attachment
	 * @return array<array<string, mixed>>
	 */
	public static function get_image_urls($attachment): array
	{
		if (is_numeric($attachment)) {
			$attachment = acf_get_attachment($attachment);
		}

		$used_urls = [ $attachment['url'] ];
		$image_urls = [
			[
				'url' => $attachment['url'],
				'width' => $attachment['width'],
				'height' => $attachment['height']
			]
		];

		foreach ($attachment['sizes'] as $key => $url) {
			// check if this a width or height key and not an image URL
			if (stripos($key, '-width') !== false || stripos($key, '-height') !== false) {
				continue;
			}

			// check if this URL has already been used
			if (in_array($url, $used_urls)) {
				continue;
			}

			$used_urls[] = $url;
			$image_urls[] = [
				'url' => $url,
				'width' => $attachment['sizes'][$key.'-width'],
				'height' => $attachment['sizes'][$key.'-height']
			];
		}

		return $image_urls;
	}

	/**
	 * Return the featured image ACF object for a resource (post) id or false
	 * if not found
	 *
	 * @param integer $resource_id
	 * @return array<string, mixed>|false
	 */
	public static function get_featured_image_acf_object(int $resource_id)
	{
		$featured_image_id = get_post_thumbnail_id($resource_id);

		if ($featured_image_id && function_exists('acf_get_attachment')) {
			return acf_get_attachment($featured_image_id);
		}

		return false;
	}

	/**
	 * Wrapper around the ACF "get_field" function that returns an array value
	 *
	 * @param string $selector
	 * @param int|string $post_id
	 * @param array<string, mixed> $opts
	 * @return array
	 */
	public static function get_field_array(string $selector, $post_id = false, array $opts = []): array
	{
		$opts = array_merge([
			'format_value' => true,
			'default' => []
		], $opts);

		if (!is_array($opts['default'])) {
			$opts['default'] = [];
		}

		if (!function_exists('get_field')) {
			return $opts['default'];
		}

		$value = get_field($selector, $post_id, $opts['format_value']);

		return is_array($value) ? $value : $opts['default'];
	}

	/**
	 * Wrapper around the ACF "get_field" function that returns a string value
	 *
	 * @param string $selector
	 * @param int|string $post_id
	 * @param array<string, mixed> $opts
	 * @return string
	 */
	public static function get_field_string(string $selector, $post_id = false, array $opts = []): string
	{
		$opts = array_merge([
			'format_value' => true,
			'default' => ''
		], $opts);

		if (!is_string($opts['default'])) {
			$opts['default'] = '';
		}

		if (!function_exists('get_field')) {
			return $opts['default'];
		}

		$value = get_field($selector, $post_id, $opts['format_value']);

		return strval($value) ? $value : $opts['default'];
	}

	/**
	 * Wrapper around the ACF "get_field" function that returns an integer value
	 *
	 * @param string $selector
	 * @param int|string $post_id
	 * @param array<string, mixed> $opts
	 * @return int
	 */
	public static function get_field_int(string $selector, $post_id = false, array $opts = []): int
	{
		$opts = array_merge([
			'format_value' => true,
			'default' => 0
		], $opts);

		if (!is_int($opts['default'])) {
			$opts['default'] = 0;
		}

		if (!function_exists('get_field')) {
			return $opts['default'];
		}

		$value = get_field($selector, $post_id, $opts['format_value']);

		if (is_bool($value)) {
			return $value ? 1 : 0;
		}

		return intval($value) ? $value : $opts['default'];
	}

	/**
	 * Wrapper around the ACF "get_sub_field" function that returns an array value
	 *
	 * @param string $selector
	 * @param array<string, mixed> $opts
	 * @return array
	 */
	public static function get_sub_field_array(string $selector, array $opts = []): array
	{
		$opts = array_merge([
			'format_value' => true,
			'default' => []
		], $opts);

		if (!is_array($opts['default'])) {
			$opts['default'] = [];
		}

		if (!function_exists('get_sub_field')) {
			return $opts['default'];
		}

		$value = get_sub_field($selector, $opts['format_value']);

		return is_array($value) ? $value : $opts['default'];
	}

	/**
	 * Wrapper around the ACF "get_sub_field" function that returns a string value
	 *
	 * @param string $selector
	 * @param array<string, mixed> $opts
	 * @return string
	 */
	public static function get_sub_field_string(string $selector, array $opts = []): string
	{
		$opts = array_merge([
			'format_value' => true,
			'default' => ''
		], $opts);

		if (!is_string($opts['default'])) {
			$opts['default'] = '';
		}

		if (!function_exists('get_sub_field')) {
			return $opts['default'];
		}

		$value = get_sub_field($selector, $opts['format_value']);

		return strval($value) ? $value : $opts['default'];
	}

	/**
	 * Wrapper around the ACF "get_sub_field" function that returns an integer value
	 *
	 * @param string $selector
	 * @param array<string, mixed> $opts
	 * @return integer
	 */
	public static function get_sub_field_int(string $selector, array $opts = []): int
	{
		$opts = array_merge([
			'format_value' => true,
			'default' => 0
		], $opts);

		if (!is_int($opts['default'])) {
			$opts['default'] = 0;
		}

		if (!function_exists('get_sub_field')) {
			return $opts['default'];
		}

		$value = get_sub_field($selector, $opts['format_value']);

		if (is_bool($value)) {
			return $value ? 1 : 0;
		}

		return $value !== false ? intval($value) : $opts['default'];
	}
}
