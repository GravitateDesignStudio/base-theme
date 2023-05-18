<?php
use ClientNamespace\Constants;

$acf_group = Constants\ACF::THEME_OPTIONS_FOOTER_BASE;

acf_add_local_field_group(array (
	'key' => 'group_' . $acf_group,
	'title' => 'Footer Options',
	'fields' => array (
		array (
			'key' => 'field_' . $acf_group . '_copyright_text',
			'label' => 'Copyright Text',
			'name' => $acf_group . '_copyright_text',
			'type' => 'text',
			'instructions' => 'The Year is automatically added at the front of the text.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => get_bloginfo('name') . '. All Rights Reserved.',
			'placeholder' => '',
			'formatting' => 'none',       // none | html
			'prepend' => '© ' . gmdate('Y') . ' ',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		)
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page', // post_type | post | page | page_template | post_category | taxonomy | options_page
				'operator' => '==',
				'value' => 'acf-theme-options-footer',        // if options_page then use: acf-options  | if page_template then use:  template-example.php
				'order_no' => 0,
				'group_no' => 1,
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',                 // side | normal | acf_after_title
	'style' => 'default',                    // default | seamless
	'label_placement' => 'top',                // top | left
	'instruction_placement' => 'label',     // label | field
	'active' => 1,
));
