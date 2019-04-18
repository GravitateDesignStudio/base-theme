<?php
namespace WPUtil;

abstract class Images
{
	/**
	 * Use imagick for processing images if available
	 *
	 * @return boolean
	 */
	public static function use_imagick_if_available(): bool
	{
		if (!class_exists('Imagick')) {
			return false;
		}

		add_filter('wp_image_editors', function() {
			return array('WP_Image_Editor_Imagick');
		});

		return true;
	}
}
