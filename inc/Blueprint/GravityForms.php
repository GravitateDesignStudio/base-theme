<?php
namespace Blueprint;

abstract class GravityForms
{
	/**
	 * Ensure the 'gravity_form' method exists and combine parameters
	 * into an options array. Returns the output as a string.
	 *
	 * @param integer $form_id
	 * @param array $opts
	 * @return string
	 */
	public static function safe_display_form(int $form_id, array $opts = []): string
	{
		if (!function_exists('gravity_form')) {
			return '<div style="color: #f00;">ERROR: Gravity Forms plugin not enabled</div>';
		}

		$display_title = $opts['display_title'] ?? false;
		$display_description = $opts['display_description'] ?? false;
		$display_inactive = $opts['display_inactive'] ?? false;
		$field_values = $opts['field_values'] ?? null;
		$ajax = $opts['ajax'] ?? false;
		$tabindex = $opts['tabindex'] ?? 0;

		return gravity_form($form_id, $display_title, $display_description, $display_inactive, $field_values, $ajax, $tabindex, false);
	}
}
