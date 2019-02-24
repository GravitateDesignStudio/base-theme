<?php
namespace Blueprint;

abstract class Images
{
	public static function safe_image_output($image, $attrs = [])
	{
		if (!class_exists('GRAV_BLOCKS')) {
			return '';
		}

		echo \GRAV_BLOCKS::image($image, $attrs);
	}

	public static function get_image_sizes()
	{
		$cached_value = \Blueprint\StaticCache::get('image_sizes');

		if ($cached_value !== null) {
			return $cached_value;
		}

		global $_wp_additional_image_sizes;

		$sizes = [];

		foreach (get_intermediate_image_sizes() as $size) {
			if (in_array($size, ['thumbnail', 'medium', 'medium_large', 'large'])) {
				$sizes[$size]['width'] = get_option("{$size}_size_w");
				$sizes[$size]['height'] = get_option("{$size}_size_h");
				$sizes[$size]['crop'] = (bool)get_option("{$size}_crop");
			} else if (isset($_wp_additional_image_sizes[$size])) {
				$sizes[$size] = array(
					'width' => $_wp_additional_image_sizes[$size]['width'],
					'height' => $_wp_additional_image_sizes[$size]['height'],
					'crop' => $_wp_additional_image_sizes[$size]['crop'],
				);
			}
		}

		\Blueprint\StaticCache::set('image_sizes', $sizes);

		return \Blueprint\StaticCache::get('image_sizes');
	}

	public static function is_cropped_size($size_name)
	{
		$sizes = self::get_image_sizes();

		// check for size name key in sizes array
		if (!isset($sizes[$size_name])) {
			return false;
		}

		// check if 'crop' key exists
		if (isset($sizes[$size_name]['crop'])) {
			return $sizes[$size_name]['crop'];
		}

		return false;
	}

	public static function get_image_sizes_from_acf_object($acf_image, $opts = [])
	{
		if (is_numeric($acf_image)) {
			$acf_image = acf_get_attachment($acf_image);
		}
		
		if (!is_array($acf_image)) {
			return [];
		}

		$wp_image_sizes = self::get_image_sizes();
		$acf_image_sizes = [];

		if (isset($acf_image['sizes']) && is_array($acf_image['sizes'])) {
			foreach ($acf_image['sizes'] as $size => $url) {
				// not a url value
				if (stripos($size, '-width') !== false || stripos($size, '-height') !== false) {
					continue;
				}

				// check for cropped size ignore
				if (isset($opts['ignore_cropped']) && $opts['ignore_cropped'] && self::is_cropped_size($size)) {
					continue;
				}

				$acf_image_sizes[$size] = array(
					'url' => $url,
					'width' => $acf_image['sizes'][$size.'-width'],
					'height' => $acf_image['sizes'][$size.'-height']
				);
			}
		}

		$acf_image_sizes['full'] = array(
			'url' => $acf_image['url'],
			'width' => $acf_image['width'],
			'height' => $acf_image['height']
		);

		return $acf_image_sizes;
	}
}
