<?php
use ClientNamespace\Constants;

$acf_group = Constants\ACF::BLOG_SETTINGS_BASE;

$blog_page_id = get_option('page_for_posts');
$archive_title_message = 'The blog archive title is controlled by the name of the page set as the blog archive under "Settings / Reading". ';

if ($blog_page_id) {
	$archive_title_message .= '<a href="' . admin_url() . 'post.php?post=' . $blog_page_id . '&action=edit">Edit the current archive page.</a>';
} else {
	$archive_title_message .= 'No page is currently set as the blog archive page.';
}

acf_add_local_field_group(array (
	'key' => 'group_' . $acf_group,
	'title' => 'Blog Settings',
	'fields' => array (
		/**
		 * Tab: Blog Archive
		 */
		array (
			'key' => 'field_'.$acf_group.'_tab_blog_archive',
			'label' => 'Blog Archive',
			'name' => 'tab_blog_archive',
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
			'key' => 'field_'.$acf_group.'_message_archive_title',
			'label' => 'Archive Title',
			'name' => $acf_group . '_message_archive_title',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => $archive_title_message,
			'new_lines' => 'wpautop',    // wpautop | br | ''
			'esc_html' => 0,             // uses the WordPress esc_html function
		),
		array (
			'key' => 'field_'.$acf_group.'_override_posts_per_page',
			'label' => 'Override Default Posts Per Page Value',
			'name' => $acf_group . '_override_posts_per_page',
			'type' => 'true_false',
			'instructions' => 'Enabling this option will allow you to override the default value under <a href="' . get_admin_url() . 'options-reading.php">Settings / Reading</a> for this archive.',
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
				ClientNamespace\Constants\DefaultValues::ARCHIVE_NO_RESULTS_MESSAGE,
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
			'key' => 'field_'.$acf_group.'_featured_posts',
			'label' => 'Featured Posts',
			'name' => $acf_group . '_featured_posts',
			'type' => 'relationship',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => ClientNamespace\Constants\CPT::BLOG,
			'taxonomy' => array (),
			'filters' => array (
				'search'
			),
			'elements' => '',
			'min' => 0,
			'max' => 2,
			'return_format' => 'id',     // object | id
		),
		array (
			'key' => 'field_' . $acf_group . '_blog_archive_blocks',
			'label' => 'Blog Archive',
			'name' => $acf_group . '_blog_archive_blocks',
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

		/**
		 * Tab: Blog Post Blocks
		 */
		array (
			'key' => 'field_' . $acf_group . '_tab_blog_post_blocks',
			'label' => 'Blog Post Blocks',
			'name' => 'tab_blog_post_blocks',
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
			'key' => 'field_' . $acf_group . '_blog_post_blocks',
			'label' => 'Blog Post',
			'name' => $acf_group . '_blog_post_blocks',
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

		/**
		 * Tab: Images
		 */
		array (
			'key' => 'field_' . $acf_group . '_tab_images',
			'label' => 'Images',
			'name' => $acf_group . '_tab_images',
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
			'key' => 'field_' . $acf_group . '_default_featured_image',
			'label' => 'Default Featured Image',
			'name' => $acf_group . '_default_featured_image',
			'instructions' => '',
			'type' => 'image',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'object',       // array | url | id
			'preview_size' => 'medium',
			'library' => 'all',       // all | uploadedTo
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		)
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page', // post_type | post | page | page_template | post_category | taxonomy | options_page
				'operator' => '==',
				'value' => 'blog-settings',        // if options_page then use: acf-options  | if page_template then use:  template-example.php
				'order_no' => 0,
				'group_no' => 1,
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',                 // side | normal | acf_after_title
	'style' => 'seamless',                    // default | seamless
	'label_placement' => 'top',                // top | left
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
