<?php
namespace WPUtil;

abstract class Performance
{
	/**
	 * Remove emojicon support
	 *
	 * @return void
	 */
	public static function remove_emojicon_support(): void
	{
		add_action('init', function() {
			// all actions related to emojis
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');

			// filter to remove TinyMCE emojis
			add_filter('tiny_mce_plugins', function($plugins) {
				if (is_array($plugins)) {
					return array_diff($plugins, array('wpemoji'));
				} else {
					return array();
				}
			});

			// disable emojicon svg url DNS prefetch
			add_filter('emoji_svg_url', '__return_false');
		});
	}

	/**
	 * Remove the jquery-migrate script
	 *
	 * @return void
	 */
	public static function remove_jquery_migrate(): void
	{
		add_filter('wp_default_scripts', function(&$scripts) {
			if (!is_admin()) {
				$scripts->remove('jquery');
				$scripts->add('jquery', false, array( 'jquery-core' ), '1.10.2');
			}
		});
	}

	/**
	 * Move the jQuery script include to the footer
	 *
	 * @return void
	 */
	public static function move_jquery_to_footer(): void
	{
		add_action('wp_enqueue_scripts', function() {
			if (is_admin()) {
		        return;
		    }

		    $wp_scripts = wp_scripts();

		    $wp_scripts->add_data('jquery', 'group', 1);
		    $wp_scripts->add_data('jquery-core', 'group', 1);
		    $wp_scripts->add_data('jquery-migrate', 'group', 1);
		}, 0);
	}

	/**
	 * Defer a list of style handlers to load on the DOMContentLoaded event
	 *
	 * @param array $styles
	 * @param boolean $debug
	 * @return void
	 */
	public static function defer_styles(array $styles, bool $debug = false)
	{
		add_filter('style_loader_tag', function($tag, $handle) use (&$styles, &$debug) {
			if (in_array($handle, $styles)) {
				$xml = simplexml_load_string($tag);
				$list = $xml->xpath('//@href');

				if (is_array($list) && count($list)) {
					$url_parts = parse_url($list[0]);
					$url = $url_parts['scheme'].'://'.$url_parts['host'].'/'.$url_parts['path'];

					add_action('wp_footer', function() use (&$handle, &$url, &$debug) {
						$js_var = preg_replace('/[^a-zA-Z0-9]/', '', $handle);
						$js_var_defer = $js_var.'Defer';

						?>
						<script type="text/javascript">
						document.addEventListener('DOMContentLoaded', function() {
							var <?php echo $js_var; ?> = document.createElement('link');
							<?php echo $js_var; ?>.rel = 'stylesheet';
							<?php echo $js_var; ?>.href = '<?php echo $url; ?>';
							<?php echo $js_var; ?>.type = 'text/css';
							<?php if ($debug) { ?><?php echo $js_var; ?>.onload = function() { console.log('<?php echo $js_var; ?> defered css loaded'); };<?php } ?>
							var <?php echo $js_var_defer; ?> = document.getElementsByTagName('link')[0];
							<?php echo $js_var_defer; ?>.parentNode.insertBefore(<?php echo $js_var; ?>, <?php echo $js_var_defer; ?>);
						});
						</script>
						<?php
					}, 9999);

					return '';
				}
			}

			return $tag;
		}, 10, 2);
	}

	/**
	 * Output a list of style handlers as inline styles
	 *
	 * @param array $styles
	 * @return void
	 */
	public static function inline_styles(array $styles): void
	{
		add_filter('style_loader_tag', function($tag, $handle) use (&$styles) {
			if (in_array($handle, $styles)) {
				$xml = simplexml_load_string($tag);
				$list = $xml->xpath('//@href');

				if (is_array($list) && count($list)) {
					$url_parts = parse_url($list[0]);
					$local_file = ABSPATH.$url_parts['path'];

					if (file_exists($local_file)) {
						return '<style>'.file_get_contents($local_file).'</style>'."\n";
					}
				}
			}

			return $tag;
		}, 10, 2);
	}

	/**
	 * Add the 'defer' attribute to a list of script handles
	 *
	 * @param array $script_handles
	 * @return void
	 */
	public static function defer_scripts(array $script_handles): void
	{
		if (is_admin()) {
			return;
		}

		add_filter('script_loader_tag', function($tag, $handle) use (&$script_handles) {
			// leave the tag alone if it's already deferred
			if (strpos($tag, ' defer') !== false) {
				return $tag;
			}

			// return the tag with the 'defer' attribute added
			if (!$script_handles || in_array($handle, $script_handles)) {
				return str_replace(' src', ' defer src', $tag);
			}

			// return the default tag
			return $tag;
		}, 10, 2);
	}

	/**
	 * Append a script to the DOM after the DOMContentLoaded event has fired
	 *
	 * @param string $name
	 * @param string $url
	 * @return void
	 */
	public static function append_script_after_load(string $name, string $url): void
	{
		$name = esc_attr($name);
		$url = esc_url($url);

		add_action('wp_footer', function() use (&$name, &$url) {
			?>
			<script type="text/javascript">
			window.addEventListener('load', function() {
				var <?php echo $name; ?> = document.createElement('script');
				<?php echo $name; ?>.src = '<?php echo $url; ?>';
				document.getElementsByTagName('body')[0].appendChild(<?php echo $name; ?>);
			});
			</script>
			<?php
		});
	}
}
