<?php
namespace WPUtil;

use WP_Post;

abstract class CustomMenuFields
{
	/**
	 * Array of custom menu field entries
	 *
	 * @var array<array<string, mixed>>
	 */
	private static $fields = [];

	/**
	 * Field prefix applied to all stored field names
	 *
	 * @var string
	 */
	private static $field_prefix = '_custom_menu_item_';

	/**
	 * Array of menu slug keys containing arrays of menu item ids
	 *
	 * @var array<string, array<int>>
	 */
	private static $menu_item_ids_cache = [];

	/**
	 * Flag indicating if the init hook has been added
	 *
	 * @var boolean
	 */
	private static $init_hook_added = false;


	/**
	 * Internal method to add the init hook onto the "wp_nav_menu_item_custom_fields"
	 * action if needed.
	 *
	 * @return void
	 */
	private static function add_init_hook()
	{
		if (self::$init_hook_added) {
			return;
		}

		// Render the registered fields in the default nav menu walker (as of WP 5.4)
		add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item, $depth, $args, $id) {
			CustomMenuFields::display_fields($item);
		}, 10, 5);

		self::$init_hook_added = true;
	}

	/**
	 * Create custom menu item fields. Each field entry is an array with the following
	 * usable keys:
	 *
	 * 'id' (required) - The id (name) of the custom menu field
	 * 'type' - Can be 'text', 'select', or 'checkbox'
	 * 'label' - Text label for the field
	 * 'values' (only used when the 'type' is 'select') - A key/value array of option values
	 * 'show_in_menu' - A string or array of strings specifying which menu slugs should display this field. An empty value will cause the field to display for all menus.
	 * 'display_callback' - A function that receives two arguments: (bool $show_field, WP_Post $menu_item). It is expected to return a boolean value indicating if it should be visible.
	 *
	 * @param array<array<string, mixed>> $fields
	 * @return void
	 */
	public static function create_fields($fields = [])
	{
		self::add_init_hook();

		$fields = array_reduce($fields, function ($acum, $field) {
			if (!isset($field['id'])) {
				error_log('CustomMenuFields::create_fields - field must have "id" value');
				return $acum;
			}

			$field['id'] = str_replace(['-', ' '], '_', $field['id']);

			if (!isset($field['type'])) {
				error_log('CustomMenuFields::create_fields - no type specified for ' . $field['id'] . '; defaulting to "text"');
				$field['type'] = 'text';
			}

			if (!isset($field['label'])) {
				$field['label'] = ucwords(str_replace('_', ' ', $field['label']));
			}

			switch ($field['type']) {
				case 'select':
					$field['values'] = $field['values'] ?? [];
					break;

				default:
					break;
			}

			if (isset($field['show_in_menu'])) {
				if (!is_array($field['show_in_menu'])) {
					$field['show_in_menu'] = [$field['show_in_menu']];
				}
			} else {
				$field['show_in_menu'] = [];
			}

			$acum[] = $field;

			return $acum;
		}, []);

		self::$fields = $fields;

		// load custom menu edit options
		add_filter('wp_setup_nav_menu_item', function ($menu_item) use ($fields) {
			foreach ($fields as $field) {
				$field_id = $field['id'];
				$menu_item->$field_id = get_post_meta($menu_item->ID, self::$field_prefix . $field_id, true);
			}

			return $menu_item;
		});

		// save custom menu edit options
		add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id, $args) use ($fields) {
			foreach ($fields as $field) {
				$field_id = $field['id'];
				$field_key = self::$field_prefix . $field_id . '_' . $menu_item_db_id;

				// phpcs:disable WordPress.Security.NonceVerification.Recommended
				if ($field['type'] === 'checkbox') {
					// phpcs:ignore
					$check_value = (isset($_REQUEST[$field_key]) && $_REQUEST[$field_key] === 'on') ? 1 : 0;
					update_post_meta($menu_item_db_id, self::$field_prefix . $field_id, $check_value);
				} elseif (isset($_REQUEST[$field_key])) {
					// phpcs:ignore
					update_post_meta($menu_item_db_id, self::$field_prefix . $field_id, $_REQUEST[$field_key]);
				}
				// phpcs:enable WordPress.Security.NonceVerification.Recommended
			}
		}, 10, 3);
	}

	/**
	 * Returns the value of the specified field id for the provided menu id
	 *
	 * @param integer $menu_id
	 * @param string $field_id
	 * @return mixed
	 */
	public static function get_value(int $menu_id, string $field_id)
	{
		return get_post_meta($menu_id, self::$field_prefix . $field_id, true);
	}

	/**
	 * Returns an array of menu item ids for the specified menu slug
	 *
	 * @param string $menu_slug
	 * @return array<int>
	 */
	private static function get_menu_item_ids(string $menu_slug): array
	{
		if (!isset(self::$menu_item_ids_cache[$menu_slug])) {
			$locations = get_nav_menu_locations();

			if (!isset($locations[$menu_slug])) {
				self::$menu_item_ids_cache[$menu_slug] = [];

				return self::$menu_item_ids_cache[$menu_slug];
			}

			$object = wp_get_nav_menu_object($locations[$menu_slug]);

			if ($object === false) {
				self::$menu_item_ids_cache[$menu_slug] = [];

				return self::$menu_item_ids_cache[$menu_slug];
			}

			$menu_items = wp_get_nav_menu_items($object->name);

			$menu_item_ids = array_map(function ($menu_post) {
				return $menu_post->ID;
			}, is_array($menu_items) ? $menu_items : []);

			self::$menu_item_ids_cache[$menu_slug] = $menu_item_ids;
		}

		return self::$menu_item_ids_cache[$menu_slug];
	}

	/**
	 * Output the markup for the registered custom menu fields
	 *
	 * @param WP_Post $menu_item
	 * @return void
	 */
	public static function display_fields(WP_Post $menu_item)
	{
		foreach (self::$fields as $field) {
			$show_field = true;

			if ($field['show_in_menu']) {
				$show_field = false;

				foreach ($field['show_in_menu'] as $menu_slug) {
					if ($show_field) {
						continue;
					}

					$menu_item_ids = self::get_menu_item_ids($menu_slug);

					if (in_array($menu_item->ID, $menu_item_ids, true)) {
						$show_field = true;
					}
				}
			}

			if (isset($field['display_callback']) && is_callable($field['display_callback'])) {
				$cb_func = $field['display_callback'];

				$show_field = $cb_func($show_field, $menu_item);
			}

			if (!$show_field) {
				continue;
			}

			switch ($field['type']) {
				case 'select':
					self::display_field_select($field, $menu_item);
					break;

				case 'checkbox':
					self::display_field_checkbox($field, $menu_item);
					break;

				case 'text':
					self::display_field_text($field, $menu_item);
					break;

				default:
					break;
			}
		}
	}

	/**
	 * Output the markup for a text field
	 *
	 * @param array $field
	 * @param WP_Post $menu_item
	 * @return void
	 */
	private static function display_field_text(array $field, WP_Post $menu_item)
	{
		$field_id = $field['id'];
		$input_name = self::$field_prefix . $field_id . '_' . $menu_item->ID;
		$input_id = 'edit-menu-item-' . $field_id . '-' . $menu_item->ID;

		// phpcs:disable Squiz.ControlStructures, Squiz.WhiteSpace
		?>
		<p class="field-custom description description-wide">
			<label for="<?php echo esc_attr($input_id); ?>">
				<?php echo esc_html($field['label']); ?><br />
				<input
					type="text"
					class="widefat"
					name="<?php echo esc_attr($input_name); ?>"
					id="<?php echo esc_attr($input_id); ?>"
					value="<?php if ($menu_item->$field_id) { echo esc_attr($menu_item->$field_id); } ?>"
				>
			</label>
		</p>
		<?php
		// phpcs:enable Squiz.ControlStructures, Squiz.WhiteSpace
	}

	/**
	 * Output the markup for a checkbox field
	 *
	 * @param array $field
	 * @param WP_Post $menu_item
	 * @return void
	 */
	private static function display_field_checkbox(array $field, WP_Post $menu_item)
	{
		$field_id = $field['id'];
		$input_name = self::$field_prefix . $field_id . '_' . $menu_item->ID;
		$input_id = 'edit-menu-item-' . $field_id . '-' . $menu_item->ID;

		// phpcs:disable Squiz.ControlStructures, Squiz.WhiteSpace
		?>
		<p class="field-custom description description-wide">
			<label for="<?php echo esc_attr($input_id); ?>">
				<input
					type="checkbox"
					name="<?php echo esc_attr($input_name); ?>"
					id="<?php echo esc_attr($input_id); ?>"
					<?php if ($menu_item->$field_id) { ?>checked<?php } ?>
				>
				<?php echo esc_html($field['label']); ?>
			</label>
		</p>
		<?php
		// phpcs:enable Squiz.ControlStructures, Squiz.WhiteSpace
	}

	/**
	 * Output the markup for a select field
	 *
	 * @param array $field
	 * @param WP_Post $menu_item
	 * @return void
	 */
	private static function display_field_select(array $field, WP_Post $menu_item)
	{
		$field_id = $field['id'];
		$input_name = self::$field_prefix . $field_id . '_' . $menu_item->ID;
		$input_id = 'edit-menu-item-' . $field_id . '-' . $menu_item->ID;

		?>
		<p class="field-custom description description-wide">
			<label for="<?php echo esc_attr($input_id); ?>">
				<?php echo esc_html($field['label']); ?><br />
				<select class="widefat" name="<?php echo esc_attr($input_name); ?>" id="<?php echo esc_attr($input_id); ?>">
					<?php
					if (isset($field['values'])) {
						foreach ($field['values'] as $key => $value) {
							// phpcs:disable Squiz.ControlStructures, Squiz.WhiteSpace
							?>
							<option
								value="<?php echo esc_attr($key); ?>"
								<?php if ($menu_item->$field_id == $key) { ?>selected<?php } ?>
							>
								<?php echo esc_html($value); ?>
							</option>
							<?php
							// phpcs:enable Squiz.ControlStructures, Squiz.WhiteSpace
						}
					}
					?>
				</select>
			</label>
		</p>
		<?php
	}
}
