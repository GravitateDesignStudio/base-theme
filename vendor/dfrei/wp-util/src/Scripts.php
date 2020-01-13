<?php
namespace WPUtil;

abstract class Scripts
{
	static $scripts = [];
	static $footer_scripts = [];
	static $hooks_registered = false;
	static $has_run = false;
	static $footer_scripts_priority = 999;

	/**
	 * Set the priority of the 'wp_footer' action used internally.
	 *
	 * @param integer $priority
	 * @return void
	 */
	public static function set_footer_scripts_priority(int $priority)
	{
		static::$footer_scripts_priority = $priority;
	}

	/**
	 * Internal callback hook for the 'wp_enqueue_scripts' action.
	 * Do not call directly.
	 *
	 * @return void
	 */
	public static function _wp_enqueue_scripts()
	{
		static::$has_run = true;

		foreach (static::$scripts as $name => $params) {
			if (!isset($params['url'])) {
				continue;
			}

			if (!isset($params['deps'])) $params['deps'] = array();
			if (!isset($params['footer'])) $params['footer'] = true;

			wp_register_script($name, $params['url'], $params['deps'], $params['version'], $params['footer']);

			if (isset($params['localize'])) {
				wp_localize_script($name, $params['localize']['name'], $params['localize']['data']);
			}

			if (isset($params['register_only']) && $params['register_only']) {
				continue;
			}

			wp_enqueue_script($name);
		}
	}

	/**
	 * Internal callback hook for the 'script_loader_tag' filter.
	 * Do not call directly.
	 *
	 * @return void
	 */
	public static function _script_loader_tag(string $tag, string $handle): string
	{
		$attrs = array();

		if (isset(static::$scripts[$handle]['async']) && static::$scripts[$handle]['async']) {
			$attrs[] = 'async';
		}

		if (isset(static::$scripts[$handle]['defer']) && static::$scripts[$handle]['defer']) {
			$attrs[] = 'defer';
		}

		if ($attrs) {
			$tag = str_replace(' src', ' '.implode(' ', $attrs).' src', $tag);
		}

		return $tag;
	}

	/**
	 * Internal callback hook for the 'wp_footer' action.
	 * Do not call directly.
	 *
	 * @return void
	 */
	public static function _wp_footer()
	{
		foreach (static::$footer_scripts as $params) {
			if (!isset($params['url'])) {
				continue;
			}

			if (isset($params['localize']) && isset($params['localize']['name']) && isset($params['localize']['data'])) {
				?>
				<script type="text/javascript">
				var <?php $params['localize']['name'] ?> = <?php echo json_encode($params['localize']['data']); ?>;
				</script>
				<?php
			}

			if (isset($params['version']) && $params['version']) {
				$join_char = (stripos($params['url'], '?') !== false) ? '&' : '?';
				$params['url'] .= $join_char.'ver='.$params['version'];
			}

			$attrs_str = 'type="text/javascript" src="'.$params['url'].'"';

			if (isset($params['async']) && $params['async']) {
				$attrs_str .= ' async';
			}

			if (isset($params['defer']) && $params['defer']) {
				$attrs_str .= ' defer';
			}

			echo "<script {$attrs_str}></script>\n";
		}
	}

	/**
	 * Internal callback hook for setting actions and filters.
	 * Do not call directly.
	 *
	 * @return void
	 */
	public static function register_hooks()
	{
		if (static::$hooks_registered) {
			return;
		}

		// register all the scripts
		add_action('wp_enqueue_scripts', [__CLASS__, '_wp_enqueue_scripts']);

		// add defer/async attributes if they are specified
		add_filter('script_loader_tag', [__CLASS__, '_script_loader_tag'], 10, 2);

		add_action('wp_footer', [__CLASS__, '_wp_footer'], static::$footer_scripts_priority);

		static::$hooks_registered = true;
	}

	/**
	 * Takes an array of script objects and updates various values within them
	 *
	 * @param array $scripts
	 * @return array
	 */
	protected static function process_scripts(array $scripts): array
	{
		$scripts_proc = [];

		foreach ($scripts as $name => $params) {
			if (!isset($params['url'])) {
				continue;
			}

			// calculate versions for scripts if they are local files
			if (!isset($params['version'])) {
				if (strpos($params['url'], get_template_directory_uri()) !== false) {
					// If Local file then get the time of when it was modified
					$file_path = str_replace(get_template_directory_uri(), get_template_directory(), $params['url']);

					if (file_exists($file_path)) {
						$params['version'] = filemtime($file_path);
					}
				} else {
					// If the value is not set to null WordPress will use it's version number as the script version
					$params['version'] = null;
				}
			}

			// add a preload tag if a preload hook is specified
			if (isset($params['preload_hook']) && $params['preload_hook']) {
				$url = $params['url'];

				if (isset($params['version'])) {
					$url .= '?ver='.$params['version'];
				}

				add_action($params['preload_hook'], function() use ($url) {
					echo '<link rel="preload" href="'.$url.'" as="script">'."\n";
				});
			}

			$scripts_proc[$name] = $params;
		}

		return $scripts_proc;
	}

	/**
	 * Enqueue an array of scripts
	 * Each item can have the following keys:
	 *     'version' (string) - version for the style enqueue
	 *     'url' (string) - URL of the CSS file to enqueue
	 *     'deps' (array) - List of style handles that this enqueue depends on
	 *     'footer' (bool) - Load the script during the 'wp_footer' function
	 *     'async' (bool) - Add the 'async' attribute to the script tag
	 *     'defer' (bool) - Add the 'defer' attribute to the script tag
	 *     'localize' (array) - An array with 'name' and 'data' keys used to localize
	 *         the script by printing inline JS after the script tag has been output.
	 *         The 'data' value will be encoded as JSON.
	 *     'preload_hook' (string) - An optional action hook name that will be used
	 *         to output a '<link rel="preload" href="..." as="script">' tag
	 *     'register_only' (bool) - If true, the script will be registered but not enqueued
	 *
	 * @param array $scripts
	 * @return void
	 */
	public static function enqueue_scripts(array $scripts)
	{
		if (static::$has_run) {
			trigger_error('Scripts have already been enqueued and the additional call to '.__METHOD__.' will have no effect. Try '.__CLASS__.'::add_to_footer instead.', E_USER_NOTICE);
		}

		static::$scripts = array_merge(static::$scripts, static::process_scripts($scripts));
		static::register_hooks();
	}

	/**
	 * Output an array of scripts directly to the footer in the 'wp_footer' action.
	 * The 'wp_footer' action priority can be controlled with WPUtil\Scripts::set_footer_scripts_priority
	 *
	 * Each item can have the following keys:
	 *     'version' (string) - version for the style enqueue
	 *     'url' (string) - URL of the CSS file to enqueue
	 *     'async' (bool) - Add the 'async' attribute to the script tag
	 *     'defer' (bool) - Add the 'defer' attribute to the script tag
	 *     'localize' (array) - An array with 'name' and 'data' keys used to localize
	 *         the script by printing inline JS after the script tag has been output.
	 *         The 'data' value will be encoded as JSON.
	 *     'preload_hook' (string) - An optional action hook name that will be used
	 *         to output a '<link rel="preload" href="..." as="script">' tag
	 *
	 * @param array $scripts
	 * @return void
	 */
	public static function add_to_footer(array $scripts)
	{
		static::$footer_scripts = array_merge(static::$footer_scripts, static::process_scripts($scripts));
		static::register_hooks();
	}
}
