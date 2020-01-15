<?php
namespace Blueprint;

abstract class MenuDropdowns
{
	private static $cpt_slug = 'menu-dropdown';
	private static $registered_menu_dropdown_ids = [];
	private static $render_hook_registered = false;
	private static $opts = [];

	public static function init($opts = [])
	{
		self::$opts = array_merge([
			'template_path' => get_template_directory() . '/menu-dropdowns/',
			'render_action' => 'wp_footer'
		], $opts);

		self::$opts['template_path'] = trailingslashit(self::$opts['template_path']);

		self::create_post_type();
		self::create_acf_fields();
		self::create_save_hook();
	}

	public static function get_child_post_ids($dropdown_menu_id)
	{
		$child_post_ids = get_post_meta($dropdown_menu_id, 'child_post_ids', true);

		return is_array($child_post_ids) ? $child_post_ids : [];
	}

	private static function create_save_hook()
	{
		add_action('acf/save_post', function ($post_id) {
			$template_type = get_field('template', $post_id);

			$child_post_ids = apply_filters('menu_dropdown/get_child_post_ids', [], $post_id, $template_type);
			$child_post_ids = apply_filters('menu_dropdown/get_child_post_ids/template=' . $template_type, [], $post_id);

			update_post_meta($post_id, 'child_post_ids', $child_post_ids);
		}, 20);
	}

	private static function create_acf_fields()
	{
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
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_' . $acf_master_group . '_template',
							'operator' => '==',
							'value' => $template_group['name'],
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'block',
				'sub_fields' => $template_fields
			];
		}, $template_groups);

		acf_add_local_field_group(array (
			'key' => 'group_' . $acf_master_group,
			'title' => 'Menu Dropdown Settings',
			'fields' => array_merge(
				array (
					array (
						'key' => 'field_' . $acf_master_group . '_template',
						'label' => 'Template',
						'name' => 'template',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => $select_options,
						'default_value' => '',
						'allow_null' => 0,
						'multiple' => 0,         // allows for multi-select
						'ui' => 0,               // creates a more stylized UI
						'ajax' => 0,
						'placeholder' => '',
						'disabled' => 0,
						'readonly' => 0,
					),
				),
				$acf_template_groups
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type', // post_type | post | page | page_template | post_category | taxonomy | options_page
						'operator' => '==',
						'value' => self::$cpt_slug,     // if options_page then use: acf-options  | if page_template then use:  template-example.php
						'order_no' => 0,
						'group_no' => 1,
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',                 // side | normal | acf_after_title
			'style' => 'default',                   // default | seamless
			'label_placement' => 'top',             // top | left
			'instruction_placement' => 'label',     // label | field
			'hide_on_screen' => [],
			'active' => 1,
			'description' => '',
		));
	}

	public static function _render_now($dropdown_id, $custom_opts = [])
	{
		$opts = array_merge([
			'render_file_name' => 'render',
			'section' => '',
			'custom_values' => []
		], $custom_opts);

		$post = get_post($dropdown_id);

		setup_postdata($post);

		$template_name = get_field('template', $dropdown_id);
		$render_file = trailingslashit(self::$opts['template_path']) . $template_name . DIRECTORY_SEPARATOR . $opts['render_file_name'] . '.php';

		if (file_exists($render_file)) {
			extract([
				'values' => get_field('template_group_' . $template_name, $dropdown_id),
				'custom_values' => $opts['custom_values']
			]);

			$container_classes = [
				'menu-dropdown',
				'menu-dropdown-' . esc_attr($template_name)
			];

			if (isset(self::$opts['container_classes']) && is_array(self::$opts['container_classes'])) {
				$container_classes = array_merge($container_classes, self::$opts['container_classes']);
			}

			$html_open = sprintf('<div class="%s" data-menu-dropdown-id="%d">', implode(' ', $container_classes), $dropdown_id);
			$html_close = '</div>';

			echo apply_filters('menu_dropdown/container_html_open', $html_open, $template_name, $dropdown_id, $opts['section']);

			include $render_file;

			echo apply_filters('menu_dropdown/container_html_close', $html_close, $template_name, $dropdown_id, $opts['section']);
		}

		wp_reset_postdata();
	}

	public static function render_dropdown_menu($menu_id)
	{
		$menu_id = (int)$menu_id;

		if (in_array($menu_id, self::$registered_menu_dropdown_ids)) {
			return;
		}

		self::$registered_menu_dropdown_ids[] = $menu_id;

		if (!self::$render_hook_registered) {
			add_action(self::$opts['render_action'], [__CLASS__, '_render_dropdown_menus']);

			self::$render_hook_registered = true;
		}
	}

	public static function _render_dropdown_menus()
	{
		if (!self::$registered_menu_dropdown_ids) {
			return;
		}

		global $post;

		foreach (self::$registered_menu_dropdown_ids as $dropdown_id) {
			self::_render_now($dropdown_id);
		}
	}

	public static function get_requested_menu_ids()
	{
		return self::$requested_menu_dropdown_ids;
	}

	public static function get_menu_dropdown_select_options($query_opts = [])
	{
		$res = new \WP_Query(
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

	private static function create_post_type()
	{
		$single_label = 'Menu Dropdown';
		$plural_label = 'Menu Dropdowns';

		register_post_type(
			self::$cpt_slug,
			array(
				'label' => $plural_label,
				'description' => '',
				'public' => true,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array('with_front' => false, 'slug' => self::$cpt_slug),
				'query_var' => true,
				'exclude_from_search' => true,
				'can_export' => true,
				'has_archive' => false,
				'menu_icon' => 'dashicons-menu',
				'supports' => array('title'),
				'labels' => array(
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
				)
			)
		);
	}
}
