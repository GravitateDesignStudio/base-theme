<?php
namespace WPUtil;

abstract class REST
{
	/**
	 * Disable the specified REST endpoints
	 *
	 * @param array $disable_endpoints
	 * @return void
	 */
	public static function disable_endpoints(array $disable_endpoints = []): void
	{
		add_filter('rest_endpoints', function($endpoints) use (&$disable_endpoints) {
			foreach ($disable_endpoints as $disable_endpoint) {
				if (isset($endpoints[$disable_endpoint])) {
					unset($endpoints[$disable_endpoint]);
				}
			}

		    return $endpoints;
		});
	}

	/**
	 * Fixes an issue with oAuth failing because it believes URL's don't match when generating secrets during authorization.
	 *
	 * @return void
	 */
	public static function fix_oauth_url_match_issue(): void
	{
		add_filter('rest_oauth.check_callback', create_function('$valid,$url,$consumer', 'return true;'), 10, 3);
	}

	/**
	 * Restrict access to the REST API to authenticated users only
	 *
	 * @param integer $minimum_user_level The minimum user level to allow access for (default is 0)
	 * @return void
	 */
	public static function restrict_to_authenticated_users(int $minimum_user_level = 0): void
	{
		add_filter('rest_authentication_errors', function($access) use (&$minimum_user_level) {
			$cur_user = wp_get_current_user();

			if (is_user_logged_in() && $cur_user && isset($cur_user->allcaps['level_'.$minimum_user_level]) && $cur_user->allcaps['level_'.$minimum_user_level]) {
				return $access;
			}

			return new \WP_Error('access-denied', 'REST API access denied', array('status' => rest_authorization_required_code()));
		});
	}

	/**
	 * Register REST controllers for a namespace
	 *
	 * @param string $namespace
	 * @param array $controllers
	 * @return void
	 */
	public static function register_routes(string $namespace, array $controllers): void
	{
		add_action('rest_api_init', function() use ($namespace, $controllers) {
			foreach ($controllers as $url => $controller) {
				new $controller($namespace, $url);
			}
		});
	}
}
