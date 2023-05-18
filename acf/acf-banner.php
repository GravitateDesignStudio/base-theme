<?php
$acf_group = 'banner';

$button_fields = WPUtil\Vendor\BlueprintBlocks::safe_get_link_fields([
	'label' => 'Button',
	'name' => $acf_group . '_button',
	'includes' => [
		'none' => 'None',
		'page' => 'Page Link',
		'url' => 'URL',
	],
]);

$tab_content = array_merge(
	array (
		array (
			'key' => 'field_' . $acf_group . '_tab_content',
			'label' => 'Content',
			'name' => $acf_group . '_tab_content',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,          // end tabs to start a new group
		),
		array (
			'key' => 'field_' . $acf_group . '_title_override',
			'label' => 'Title (override)',
			'name' => $acf_group . '_title_override',
			'type' => 'text',
			'instructions' => 'The default page title will be used if this field is empty',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'formatting' => 'none',       // none | html
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		)
	),
	$button_fields
);

$tab_background = array_merge(
	array(
		array (
			'key' => 'field_' . $acf_group . '_tab_background',
			'label' => 'Background',
			'name' => $acf_group . '_tab_background',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,          // end tabs to start a new group
		)
	),
	ClientNamespace\Banners::get_banner_background_fields()
);

acf_add_local_field_group(array (
	'key' => 'group_' . $acf_group,
	'title' => 'Banner Settings',
	'fields' => array_merge(
		$tab_content,
		$tab_background
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type', // post_type | post | page | page_template | post_category | taxonomy | options_page
				'operator' => '!=',
				'value' => 'global-block',      // if options_page then use: acf-options  | if page_template then use:  template-example.php
				'order_no' => 0,
				'group_no' => 1,
			),
			array (
				'param' => 'post_type', // post_type | post | page | page_template | post_category | taxonomy | options_page
				'operator' => '!=',
				'value' => 'hellobar',      // if options_page then use: acf-options  | if page_template then use:  template-example.php
				'order_no' => 0,
				'group_no' => 1,
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',                // side | normal | acf_after_title
	'style' => 'default',                   // default | seamless
	'label_placement' => 'top',             // top | left
	'instruction_placement' => 'label',     // label | field
	'hide_on_screen' => array (
	  // 0 => 'permalink',
	  1 => 'the_content',
	  // 2 => 'excerpt',
	  // 3 => 'custom_fields',
	  // 4 => 'discussion',
	  // 5 => 'comments',
	  // 6 => 'revisions',
	  // 7 => 'slug',
	  // 8 => 'author',
	  // 9 => 'format',
	  // 10 => 'featured_image',
	  // 11 => 'categories',
	  // 12 => 'tags',
	  // 13 => 'send-trackbacks',
	),
	'active' => 1,
	'description' => '',
));
