<?php
namespace WPUtil;

abstract class Security
{
	/**
	 * Disable admin dashboard access for specific roles with an option to redirect to a specific URL
	 *
	 * @param array $roles
	 * @param string $redirect_url
	 * @return void
	 */
	public static function disable_dashboard_access_for_roles(array $roles, string $redirect_url = ''): void
	{
		add_action('init', function() use (&$roles, &$redirect_url) {
			if (is_admin() && !defined('DOING_AJAX')) {
				foreach ($roles as $role) {
					if (current_user_can($role)) {
						wp_redirect($redirect_url ? $redirect_url : home_url());
						exit;
					}
				}
			}
		});
	}

	/**
	 * Set the minimum user level for dashboard access with an option to redirect to a specific URL
	 *
	 * @param integer $min_level
	 * @param string $redirect_url
	 * @return void
	 */
	public static function set_mimimum_dashboard_access_level(int $min_level = 1, string $redirect_url = ''): void
	{
		add_action('init', function() use (&$min_level, &$redirect_url) {
			if (is_admin() && !defined('DOING_AJAX')) {
				$cur_user = wp_get_current_user();

				if (!$cur_user || !isset($cur_user->allcaps['level_'.$min_level]) || !$cur_user->allcaps['level_'.$min_level]) {
					wp_redirect($redirect_url ? $redirect_url : home_url());
					exit;
				}
			}
		});
	}

	/**
	 * Disable XML RPC suppport
	 *
	 * @return void
	 */
	public static function disable_xmlrpc(): void
	{
		add_filter('xmlrpc_enabled', '__return_false');
	}

	/**
	 * Remove WP version meta information from the <head>
	 *
	 * @return void
	 */
	public static function remove_wp_version_meta(): void
	{
		add_filter('the_generator', '__return_false');
	}
}
