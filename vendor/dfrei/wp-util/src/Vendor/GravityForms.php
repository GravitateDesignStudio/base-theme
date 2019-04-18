<?php
namespace WPUtil\Vendor;

abstract class GravityForms
{
	protected static $_form_choices;
	protected static $_script_srcs = array();

	/**
	 * Get a list of all forms in a key/value array
	 * The form id is the key and the form title is the value
	 *
	 * @return array
	 */
	public static function get_all_forms(): array
	{
		if (!self::$_form_choices) {
			$form_choices = array();

            if (method_exists('RGFormsModel', 'get_forms')) {
			    $forms = \RGFormsModel::get_forms(null, 'title');

                foreach($forms as $form) {
			        $form_choices[$form->id] = $form->title;
			    }
			}

			self::$_form_choices = $form_choices;
		}

		return self::$_form_choices;
	}

	/**
	 * Add filters and actions to move scripts to the HTML footer
	 *
	 * @return void
	 */
	public static function move_scripts_to_footer(): void
	{
		// add_filter('gform_init_scripts_footer', '__return_true');
		add_filter('gform_get_form_filter', array(__CLASS__, '_move_scripts_form_filter'), 10, 2);
		add_action('wp_footer', array(__CLASS__, '_move_scripts_footer_print'), 999);
	}

	/**
	 * Callback for moving scripts to the HTML footer
	 * Do not call directly
	 *
	 * @param string $form_string
	 * @param object $form
	 * @return void
	 */
	public static function _move_scripts_form_filter($form_string, $form)
	{
		$matches = array();

		preg_match_all("/<script\b[^>]*>([\s\S]*?)<\/script>/", $form_string, $matches);

		if (isset($matches[1]) && is_array($matches[1])) {
			if (is_array($matches[1])) {
				self::$_script_srcs = array_merge(self::$_script_srcs, array_values($matches[1]));
			} else {
				self::$_script_srcs[] = $matches[1];
			}

			return preg_replace("/<script\b[^>]*>([\s\S]*?)<\/script>/", '', $form_string);
		}

		return $form_string;
	}

	/**
	 * Callback for moving scripts to the HTML footer
	 * Do not call directly
	 *
	 * @return void
	 */
	public static function _move_scripts_footer_print()
	{
		$scripts = array_unique(self::$_script_srcs);

		?>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				<?php echo implode("\n\n", $scripts); ?>
			});
		</script>
		<?php
	}

	/**
	 * Wrap the inline JS that Gravity Forms outputs with an event
	 * listener that fires after the page has been loaded.
	 * This ensures that jQuery is available even if it has been
	 * deferred. Without this hook, jQuery must be loaded in the
	 * <head> and cannot be deferred.
	 *
	 * @return void
	 */
	public static function safely_output_inline_scripts(): void
	{
		add_filter('gform_cdata_open', function($js) {
			if ((defined('DOING_AJAX') && DOING_AJAX) || isset($_POST['gform_ajax'])) {
				return $js;
			}

			return "document.addEventListener('DOMContentLoaded', function() { ";
		});

		add_filter('gform_cdata_close', function($js) {
			if ((defined('DOING_AJAX') && DOING_AJAX) || isset($_POST['gform_ajax'])) {
				return $js;
			}

			return " });";
		});
	}
}
