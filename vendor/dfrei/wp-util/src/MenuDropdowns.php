<?php
namespace WPUtil;

use WPUtil\Vendor\ACF;
use WP_Query;

abstract class MenuDropdowns
{
	/**
	 * The menu dropdown CPT slug
	 *
	 * @var string
	 */
	private static $cpt_slug = 'menu-dropdown';

	/**
	 * Array of menu dropdown ids that have been enqueued for rendering
	 *
	 * @var array<int>
	 */
	private static $registered_menu_dropdown_ids = [];

	/**
	 * Flag indicating that the render hook has been registered
	 *
	 * @var boolean
	 */
	private static $render_hook_registered = false;

	/**
	 * Options passed via the "init" method
	 *
	 * @var array<string, mixed>
	 */
	private static $opts = [];

	/**
	 * Initialize menu dropdown support
	 *
	 * @param array $opts
	 * @return void
	 */
	public static function init($opts = [])
	{
		self::$opts = array_merge([
			'template_path' => get_template_directory() . '/menu-dropdowns/',
			'render_action' => 'wp_footer',
			'container_classes' => []
		], $opts);

		self::$opts['template_path'] = trailingslashit(self::$opts['template_path']);

		self::create_post_type();
		self::create_acf_fields();
		self::create_save_hook();
	}

	/**
	 * Return an array of the calculated child post ids for the specified dropdown
	 * menu id. The calculated child post ids are created by using either the
	 * "menu_dropdown/get_child_post_ids" or "menu_dropdown/get_child_post_ids/template=<name>"
	 * filters. An example use of this would be determining if a menu link or dropdown
	 * menu needs to be active/shown depending on the post ID value of the current
	 * post/page.
	 *
	 * @param integer $dropdown_menu_id
	 * @return array<int>
	 */
	public static function get_child_post_ids(int $dropdown_menu_id): array
	{
		$child_post_ids = get_post_meta($dropdown_menu_id, 'child_post_ids', true);

		return is_array($child_post_ids) ? $child_post_ids : [];
	}

	/**
	 * Creates the save hook that updates the "child_post_ids" value when menu dropdown
	 * CPTs are saved.
	 *
	 * @return void
	 */
	private static function create_save_hook()
	{
		$cpt_slug = self::$cpt_slug;

		add_action('acf/save_post', function ($post_id) use ($cpt_slug) {
			if (get_post_type($post_id) !== $cpt_slug) {
				return;
			}

			$template_type = ACF::get_field_string('template', $post_id);

			$child_post_ids = apply_filters('menu_dropdown/get_child_post_ids', [], $post_id, $template_type);
			$child_post_ids = apply_filters('menu_dropdown/get_child_post_ids/template=' . $template_type, [], $post_id);

			update_post_meta($post_id, 'child_post_ids', $child_post_ids);
		}, 20);
	}

	/**
	 * Create ACF field groups for the menu dropdown CPT posts
	 *
	 * @return void
	 */
	private static function create_acf_fields()
	{
		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		$template_paths = glob(self::$opts['template_path'] . '*', GLOB_ONLYDIR);
		$template_groups = [];

		if ($template_paths) {
			$template_groups = array_map(function ($path) {
				return [
					'path' => $path,
					'name' => basename($path),
					'display_name' => ucwords(str_replace(['-', '_'], ' ', basename($path)))
				];
			}, $template_paths);
		}

		$acf_master_group = 'menu_dropdown';
		$select_options = [];

		foreach ($template_groups as $group) {
			$select_options[$group['name']] = $group['display_name'];
		}

		$acf_template_groups = array_map(function ($template_group) use ($acf_master_group) {
			$template_fields_file = trailingslashit($template_group['path']) . 'fields.php';

			if (!file_exists($template_fields_file)) {
				return [];
			}

			$template_fields = include $template_fields_file;

			return [
				'key' => 'field_' . $acf_master_group . '_template_group_' . $template_group['name'],
				'label' => $template_group['display_name'] . ' Settings',
				'name' => 'template_group_' . $template_group['name'],
				'type' => 'group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => [
					[
						[
							'field' => 'field_' . $acf_master_group . '_template',
							'operator' => '==',
							'value' => $template_group['name']
						]
					]
				],
				'wrapper' => [
					'width' => '',
					'class' => '',
					'id' => ''
				],
				'layout' => 'block',
				'sub_fields' => $template_fields
			];
		}, $template_groups);

		acf_add_local_field_group([
			'key' => 'group_' . $acf_master_group,
			'title' => 'Menu Dropdown Settings',
			'fields' => array_merge(
				[
					[
						'key' => 'field_' . $acf_master_group . '_template',
						'label' => 'Template',
						'name' => 'template',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id' => ''
						],
						'choices' => $select_options,
						'default_value' => '',
						'allow_null' => 0,
						'multiple' => 0,         // allows for multi-select
						'ui' => 0,               // creates a more stylized UI
						'ajax' => 0,
						'placeholder' => '',
						'disabled' => 0,
						'readonly' => 0,
					]
				],
				$acf_template_groups
			),
			'location' => [
				[
					[
						'param' => 'post_type', // post_type | post | page | page_template | post_category | taxonomy | options_page
						'operator' => '==',
						'value' => self::$cpt_slug,     // if options_page then use: acf-options  | if page_template then use:  template-example.php
						'order_no' => 0,
						'group_no' => 1
					]
				]
			],
			'menu_order' => 0,
			'position' => 'normal',                 // side | normal | acf_after_title
			'style' => 'default',                   // default | seamless
			'label_placement' => 'top',             // top | left
			'instruction_placement' => 'label',     // label | field
			'hide_on_screen' => [],
			'active' => 1,
			'description' => '',
		]);
	}

	/**
	 * Render the specified dropdown menu immediately. Valid option keys:
	 *
	 * 'render_file_name' (string | default: 'render')
	 * 'section' (string | default: '')
	 * 'custom_values' (array | default: [])
	 * 'echo' (boolean | default: true)
	 * 'container_classes' (array | default: [])
	 *
	 * @param integer $dropdown_id
	 * @param array $custom_opts
	 * @return void
	 */
	public static function render_now(int $dropdown_id, array $custom_opts = [])
	{
		$opts = array_merge([
			'render_file_name' => 'render',
			'section' => '',
			'custom_values' => [],
			'echo' => true,
			'container_classes' => []
		], $custom_opts);

		if (!$opts['echo']) {
			ob_start();
		}

		$post = get_post($dropdown_id);

		setup_postdata($post);

		$template_name = ACF::get_field_string('template', $dropdown_id);
		$render_file = trailingslashit(self::$opts['template_path']) . $template_name . DIRECTORY_SEPARATOR . $opts['render_file_name'] . '.php';

		if (file_exists($render_file)) {
			extract([
				'values' => ACF::get_field_array('template_group_' . $template_name, $dropdown_id),
				'custom_values' => $opts['custom_values']
			]);

			$container_classes = [
				'menu-dropdown',
				'menu-dropdown-' . esc_attr($template_name)
			];

			if (isset(self::$opts['container_classes']) && is_array(self::$opts['container_classes'])) {
				$container_classes = array_merge($container_classes, self::$opts['container_classes']);
			}

			if (is_array($opts['container_classes'])) {
				$container_classes = array_merge($container_classes, $opts['container_classes']);
			}

			// phpcs:ignore
			echo apply_filters('menu_dropdown/pre_container_html', '', $template_name, $dropdown_id, $opts['section']);

			$html_open = sprintf('<div class="%s" data-menu-dropdown-id="%d">', implode(' ', $container_classes), $dropdown_id);
			$html_close = '</div>';

			// phpcs:ignore
			echo apply_filters('menu_dropdown/container_html_open', $html_open, $template_name, $dropdown_id, $opts['section']);

			include $render_file;

			// phpcs:ignore
			echo apply_filters('menu_dropdown/container_html_close', $html_close, $template_name, $dropdown_id, $opts['section']);

			// phpcs:ignore
			echo apply_filters('menu_dropdown/post_container_html', '', $template_name, $dropdown_id, $opts['section']);
		}

		wp_reset_postdata();

		$ret_val = '';

		if (!$opts['echo']) {
			$ret_val = ob_get_contents();
			ob_end_clean();
		}

		return $ret_val;
	}

	/**
	 * Enqueue the specified dropdown menu id for rendering
	 *
	 * @param integer $menu_id
	 * @return void
	 */
	public static function render_dropdown_menu($menu_id)
	{
		$menu_id = absint($menu_id);

		if (in_array($menu_id, self::$registered_menu_dropdown_ids, true)) {
			return;
		}

		self::$registered_menu_dropdown_ids[] = $menu_id;

		if (!self::$render_hook_registered) {
			add_action(self::$opts['render_action'], [__CLASS__, 'render_dropdown_menus']);

			self::$render_hook_registered = true;
		}
	}

	/**
	 * Trigger the rendering of all enqueued dropdown menus
	 *
	 * @return void
	 */
	public static function render_dropdown_menus()
	{
		if (!self::$registered_menu_dropdown_ids) {
			return;
		}

		global $post;

		foreach (self::$registered_menu_dropdown_ids as $dropdown_id) {
			self::render_now($dropdown_id);
		}
	}

	/**
	 * Return a key/value array of dropdown menu ids and labels suitable for use
	 * in a select control
	 *
	 * @param array $query_opts Additional options to pass to WP_Query
	 * @return array<int, string>
	 */
	public static function get_menu_dropdown_select_options($query_opts = []): array
	{
		$res = new WP_Query(
			array_merge(
				[
					'post_type' => self::$cpt_slug,
					'posts_per_page' => -1,
					'post_status' => 'publish'
				],
				$query_opts
			)
		);

		$dropdown_posts = [0 => 'None'];

		if ($res->posts) {
			foreach ($res->posts as $post_item) {
				$dropdown_posts[$post_item->ID] = $post_item->post_title;
			}
		}

		return $dropdown_posts;
	}

	/**
	 * Create the menu dropdown CPT
	 *
	 * @return void
	 */
	private static function create_post_type()
	{
		$single_label = 'Menu Dropdown';
		$plural_label = 'Menu Dropdowns';

		// phpcs:ignore
		$cpt_opts = apply_filters('menu_dropdown/cpt_opts', [
			'label' => $plural_label,
			'description' => '',
			'public' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menu' => false,
			'capability_type' => 'page',
			'map_meta_cap' => true,
			'hierarchical' => false,
			'rewrite' => ['with_front' => false, 'slug' => self::$cpt_slug],
			'query_var' => true,
			'exclude_from_search' => true,
			'can_export' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-menu',
			'supports' => ['title'],
			'labels' => [
				'name' => $plural_label,
				'singular_name' => $single_label,
				'menu_name' => $plural_label,
				'add_new' => 'Add ' . $single_label,
				'add_new_item' => 'Add New ' . $single_label,
				'edit' => 'Edit',
				'edit_item' => 'Edit ' . $single_label,
				'new_item' => 'New ' . $single_label,
				'view' => 'View ' . $single_label,
				'view_item' => 'View ' . $single_label,
				'search_items' => 'Search ' . $plural_label,
				'not_found' => 'No ' . $plural_label . ' Found',
				'not_found_in_trash' => 'No ' . $plural_label . ' Found in Trash',
				'parent' => 'Parent ' . $single_label,
			]
		]);

		register_post_type(self::$cpt_slug, $cpt_opts);
	}
}
