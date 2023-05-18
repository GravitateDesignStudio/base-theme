<?php
use ClientNamespace\Constants;

$acf_group = Constants\ACF::THEME_OPTIONS_SEARCH_BASE;

acf_add_local_field_group(array (
	'key' => 'group_' . $acf_group,
	'title' => 'Search Settings',
	'fields' => array (
		array (
			'key' => 'field_'.$acf_group.'_override_posts_per_page',
			'label' => 'Override Default Posts Per Page Value',
			'name' => $acf_group . '_override_posts_per_page',
			'type' => 'true_false',
			'instructions' => 'Enabling this option will allow you to override the default value under <a href="' . get_admin_url() . 'options-reading.php">Settings / Reading</a> for the amount of search results shown.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'ui' => 1,
			'ui_on_text' => 'Yes',
			'ui_off_text' => 'No',
			'default_value' => 0,
		 ),
		 array (
			'key' => 'field_'.$acf_group.'_custom_posts_per_page',
			'label' => 'Custom Posts Per Page Count',
			'name' => $acf_group . '_custom_posts_per_page',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_'.$acf_group.'_override_posts_per_page',
						'operator' => '==',
						'value' => 1,
					),
				),
			),
			'wrapper' => array (
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => 12,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_'.$acf_group.'_no_results_message',
			'label' => 'No Results Message',
			'name' => $acf_group . '_no_results_message',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => __(
				ClientNamespace\Constants\DefaultValues::SEARCH_NO_RESULTS_MESSAGE,
				ClientNamespace\Constants\TextDomains::DEFAULT
			),
			'placeholder' => '',
			'formatting' => 'none', // none | html
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_' . $acf_group . '_search_archive_blocks',
			'label' => 'Search Archive',
			'name' => $acf_group . '_search_archive_blocks',
			'type' => 'clone',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'clone' => array (
				0 => 'group_grav_blocks',
			),
			'display' => 'seamless',
			'layout' => 'block',
			'prefix_label' => 1,
			'prefix_name' => 1,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page', // post_type | post | page | page_template | post_category | taxonomy | options_page
				'operator' => '==',
				'value' => 'acf-theme-options-search',        // if options_page then use: acf-options  | if page_template then use:  template-example.php
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
	'description' => '',
));
