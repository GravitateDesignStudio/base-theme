<?php
use ClientNamespace\Constants;

$acf_group = Constants\ACF::THEME_OPTIONS_SOCIAL_BASE;

$social_icons_list = WPUtil\SVG::get_svg_list('social', ['label_includes_dir' => false]);
$social_icon_choices = [];

foreach ($social_icons_list as $icon) {
	$social_icon_choices[$icon['name']] = $icon['label'];
}

acf_add_local_field_group(array (
	'key' => 'group_' . $acf_group,
	'title' => 'Social Media Settings',
	'fields' => array (
		array (
			'key' => 'field_' . $acf_group . '_tab_accounts',
			'label' => 'Accounts',
			'name' => $acf_group . '_tab_accounts',
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
			'key' => 'field_' . $acf_group . '_site_links',
			'label' => 'Social Links',
			'name' => $acf_group . '_site_links',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => '',
			'layout' => 'table',         // table | block | row
			'button_label' => 'Add Social Media Link',
			'sub_fields' => array (
				array (
					'key' => 'field_' . $acf_group . '_link_title',
					'label' => 'Title',
					'name' => 'title',
					'type' => 'text',
					'instructions' => '',
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
				),
				array (
					'key' => 'field_' . $acf_group . '_link_url',
					'label' => 'URL',
					'name' => 'url',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'https://',
					'formatting' => 'none',       // none | html
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_' . $acf_group . '_link_icon',
					'label' => 'Icon',
					'name' => 'icon',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => $social_icon_choices,
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
		),
		array (
			'key' => 'field_' . $acf_group . '_twitter_username',
			'label' => 'Twitter Username',
			'name' => $acf_group . '_twitter_username',
			'type' => 'text',
			'instructions' => 'This field is used as a value for the "@username" portion of the Twitter share URL',
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
			'prepend' => '@',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),

		array (
			'key' => 'field_' . $acf_group . '_tab_share_urls',
			'label' => 'Share URLs',
			'name' => $acf_group . '_tab_share_urls',
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
			'key' => 'field_' . $acf_group . '_share_urls_instructions',
			'label' => 'Instructions',
			'name' => $acf_group . '_share_urls_instructions',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'These fields modify the share URLs used by social media platforms. They should only be modified if you\'re having trouble with a link working correctly.<br><br>The following variables can be used:<br><br><code>[post_link]</code> - The link to the current post<br><code>[post_title]</code> - The title of the current post<br><code>[twitter_username]</code> - Your Twitter username (can only be used in the Twitter share link)',
			'new_lines' => 'wpautop',    // wpautop | br | ''
			'esc_html' => 0,             // uses the WordPress esc_html function
		),
		array (
			'key' => 'field_' . $acf_group . '_twitter_share_url',
			'label' => 'Twitter Share URL',
			'name' => $acf_group . '_twitter_share_url',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'https://twitter.com/intent/tweet?text=[post_title]%20[twitter_username]%20[post_link]',
			'placeholder' => '',
			'formatting' => 'none',       // none | html
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_' . $acf_group . '_facebook_share_url',
			'label' => 'Facebook Share URL',
			'name' => $acf_group . '_facebook_share_url',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'https://www.facebook.com/sharer/sharer.php?u=[post_link]',
			'placeholder' => '',
			'formatting' => 'none',       // none | html
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_' . $acf_group . '_linkedin_share_url',
			'label' => 'LinkedIn Share URL',
			'name' => $acf_group . '_linkedin_share_url',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'https://www.linkedin.com/shareArticle?url=[post_link]&title=[post_title]',
			'placeholder' => '',
			'formatting' => 'none',       // none | html
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page', // post_type | post | page | page_template | post_category | taxonomy | options_page
				'operator' => '==',
				'value' => 'acf-theme-options-social',        // if options_page then use: acf-options  | if page_template then use:  template-example.php
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
