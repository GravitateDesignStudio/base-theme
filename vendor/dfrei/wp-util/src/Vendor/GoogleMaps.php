<?php
namespace WPUtil\Vendor;

abstract class GoogleMaps
{
	public static $registered_maps = [];
	public static $api_key = '';
	public static $script_name = 'google-maps';
	public static $script_deps = [];
	public static $js_init_callback = 'google_maps_init';
	public static $js_event_name = 'googleMapLoaded';
	public static $js_init_function = null;
	public static $defer_api_script = true;

	public static $init_done = false;


	/**
	 * Initialize the Google Maps API script and instance variables
	 * Options include:
	 *     'api_key' (string) - REQUIRED - Your Google Maps API key
	 *     'script_name' (string) - Internal WP script handle (default: 'google-maps')
	 *     'script_deps' (array) - Array of script handles that the API script depends on
	 *     'js_init_callback' (string) - JavaScript function name that will be created inline and executed upon load (default: 'google_maps_init')
	 *     'js_init_function' (function) - inline JavaScript function that will be added to the page markup and used to initialize the map(s)
	 *     'js_event_name' (string) - JavaScript event name that will be dispatched on 'window' for each registered map upon load (default: 'googleMapLoaded')
	 *     'defer_api_script' (bool) - Add a 'defer' attribute to the enqueued google maps api script (default: true)
	 *     'register_key_with_acf' (bool) - Register the API key with ACF after init (default: false)
	 *
	 * @param array $opts
	 * @return void
	 */
	public static function init(array $opts = []): void
	{
		if (!isset($opts['api_key'])) {
			throw new \Exception(__METHOD__.' - Google Maps API key must be set with "api_key" option key');
		}

		self::$api_key = $opts['api_key'];
		self::$script_name = $opts['script_name'] ?? 'google-maps';
		self::$script_deps = $opts['script_deps'] ?? [];
		self::$js_init_callback = $opts['js_init_callback'] ?? 'google_maps_init';
		self::$js_event_name = $opts['js_event_name'] ?? 'googleMapLoaded';
		self::$js_init_function = ($opts['js_init_function'] && is_callable($opts['js_init_function'])) ? $opts['js_init_function'] : null;
		self::$defer_api_script = $opts['defer_api_script'] ?? true;

		self::$init_done = true;

		$register_key_with_acf = $opts['register_key_with_acf'] ?? false;

		if ($register_key_with_acf) {
			self::register_key_for_acf(self::$api_key);
		}
	}

	/**
	 * Register a Google Maps API key with ACF
	 *
	 * @param string $key
	 * @return void
	 */
	public static function register_key_for_acf(string $key): void
	{
		add_filter('acf/fields/google_map/api', function ($api) use ($key) {
			$api['key'] = $key;

			return $api;
		});
	}

	/**
	 * Check if the Google Maps API script is registered, enqueued, or loaded
	 *
	 * @return boolean
	 */
	public static function google_map_is_used(): bool
	{
		return wp_script_is(self::$script_name, 'registered') ||
			wp_script_is(self::$script_name, 'enqueued') ||
			wp_script_is(self::$script_name, 'done') ||
			wp_script_is(self::$script_name, 'todo');
	}

	/**
	 * Enqueue the Google Maps API script and create the necessary inline JS
	 * to dispatch loaded events for all registered maps
	 * GoogleMaps::init must be called first
	 *
	 * @return void
	 */
	public static function enqueue_google_maps_js_script(): void
	{
		if (self::google_map_is_used()) {
			return;
		}

		if (!self::$api_key) {
			throw new \Exception(__METHOD__.' - Google Maps API key must be set using '.__CLASS__.'::init');
		}

		wp_enqueue_script(self::$script_name, 'https://maps.googleapis.com/maps/api/js?key='.self::$api_key.'&callback='.self::$js_init_callback, self::$script_deps, null, true);

		add_filter('script_loader_tag', function($tag, $handle, $src) {
			if (!GoogleMaps::google_map_is_used() || $handle !== GoogleMaps::$script_name) {
				return $tag;
			}

			$attrs = [];

			if (GoogleMaps::$defer_api_script) {
				$attrs[] = 'defer';
			}

			if ($attrs) {
				$tag = str_replace(' src', ' '.implode(' ', $attrs).' src', $tag);
			}

			$init_data = [
				'eventName' => GoogleMaps::$js_event_name,
				'registeredMaps' => GoogleMaps::$registered_maps
			];

			$init_function = GoogleMaps::$js_init_function ?? function ($init_data) {
				?>
				var initData = <?php echo json_encode($init_data); ?>;

				initData.registeredMaps.forEach(function (map) {
					var loadedEvent = new CustomEvent(initData.eventName, { detail: map });

					window.dispatchEvent(loadedEvent);
				});
				<?php
			};

			?>
			<script type='text/javascript'>
			function <?php echo GoogleMaps::$js_init_callback; ?>() {
				<?php $init_function($init_data); ?>
			}
			</script>
			<?php

			return $tag;
		}, 10, 3);
	}

	/**
	 * Register a map with optional data that will be dispatched in a
	 * JavaScript event once the Google Maps API script has loaded
	 *
	 * @param string $map_id
	 * @param array $opts
	 * @return void
	 */
	public static function register_map(string $map_id, array $opts = []): void
	{
		if (!self::$init_done) {
			throw new \Exception(__METHOD__.' - Google Maps class not initialized. '.__CLASS__.'::init() must be called first.');
		}

		self::enqueue_google_maps_js_script();

		self::$registered_maps[] = array(
			'id' => $map_id,
			'opts' => $opts
		);
	}
}
