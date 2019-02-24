<?php
namespace Blueprint;

abstract class Blocks
{
	public static function safe_get_link_fields($params)
	{
		if (!class_exists('GRAV_BLOCKS')) {
			return [];
		}

		$fields = \GRAV_BLOCKS::get_link_fields($params);

		if (!is_array($fields) || !isset($fields['grav_link_fields'])) {
			return [];
		}

		return $fields['grav_link_fields'];
	}
}
