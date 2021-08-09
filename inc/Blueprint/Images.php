<?php
namespace Blueprint;

abstract class Images
{
	public static function safe_image_output($image, $attrs = [])
	{
		if (!class_exists('GRAV_BLOCKS')) {
			return '';
		}

		// phpcs:ignore
		echo \GRAV_BLOCKS::image($image, $attrs);
	}
}
