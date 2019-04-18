<?php
namespace WPUtil;

abstract class Plugins
{
	/**
	 * Force a list of plugins to be active if they aren't currently enabled
	 *
	 * @param array $plugins
	 * @return void
	 */
	public static function force_activate_plugins(array $plugins): void
	{
		add_action(is_admin() ? 'admin_init' : 'wp', function() use (&$plugins) {
			if (!is_admin()) {
				include_once (ABSPATH.'wp-admin/includes/plugin.php');
			}

			foreach ($plugins as $plugin) {
				if (!is_plugin_active($plugin)) {
					activate_plugin($plugin);
				}
			}
		});
	}

	/**
	 * Restrict a list of plugins from having the ability to be updated
	 *
	 * @param array $plugins
	 * @return void
	 */
	public static function disallow_updates(array $plugins): void
	{
		add_filter('site_transient_update_plugins', function($value) use (&$plugins) {
			foreach ($plugins as $plugin) {
				if (isset($value->response) && isset($value->response[$plugin])) {
					unset($value->response[$plugin]);
				}
			}
 		   	
			return $value;
	   });
	}
}
