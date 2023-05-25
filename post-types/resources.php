<?php 
register_extended_post_type( 'resource', [
        # Add some custom columns to the admin screen:
		'admin_cols' => array(
            'featured_image' => array(
				'title'          => 'Thumbnail',
				'featured_image' => 'thumbnail',
                'width'          => 50,
                'height'         => 50,
			),
            'title',
			'media' => array(
                'taxonomy' => 'media'
            ),
            'topic' => array(
				'taxonomy' => 'topic'
			),
            'published' => array(
                'title'      => 'Published',
                'post_field' => 'post_date',
            ),
		),

        'menu_icon' => 'dashicons-book-alt',

		# Add a dropdown filter to the admin screen:
		'admin_filters' => array(
			'media' => array(
				'taxonomy' => 'media'
            ),
            'topic' => array(
				'taxonomy' => 'topic'
			)
		)
] );

register_extended_taxonomy( 'media', 'resource', [], [
    'singular' => 'Media',
	'plural'   => 'Media',
] );

register_extended_taxonomy( 'topic', 'resource' );

$block = 'cpt_resources';

acf_add_local_field_group(array (
    'key' => 'group_'.$block,
    'title' => 'Resources Fields',
    'fields' => array (
        array (
            'key' => 'field_'.$block.'_description',
            'label' => 'Description',
            'name' => $block.'_description',
            'type' => 'wysiwyg',
            'tabs' => 'all',  // all | visual | text
            'toolbar' => 'full',  // full | basic
            'media_upload' => 1,
        ),
        array (
            'key' => 'field_'.$block.'_file',
            'label' => 'File',
            'name' => $block.'_file',
            'type' => 'file',
            'return_format' => 'array',  // array | url | id
            'library' => 'all',  // all | uploadedTo
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'post_type', // post_type | post | page | page_template | post_category | taxonomy | options_page
                'operator' => '==',
                'value' => 'resource', // if options_page then use: acf-options  | if page_template then use:  template-example.php or template-folder/template-example.php
                'order_no' => 0,
                'group_no' => 1,
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',  // side | normal | acf_after_title
    'style' => 'default',  // default | seamless
    'label_placement' => 'top',  // top | left
    'instruction_placement' => 'label',  // label | field
    'hide_on_screen' => array (
        1 => 'the_content',
    ),
    'active' => 1,
));

add_action('rest_api_init', function () {
	/**
	 * Add a 'card_markup' field to the results returned by the
	 * /wp/v2/posts endpoint
	 */
	register_rest_field('resource', 'card_markup', array(
		'get_callback' => function ($post) {
			return WPUtil\Component::render_to_string(
				'components/cards/card',
				[
					'post_id' => $post['id']
				]
			);
		}
	));

	/**
	 * Add filters to resource queries
	 */
	// add_filter('rest_resource_query', function ($args, $request) {
	// 	$args['posts_per_page'] = 12; // @TODO: move to constants

	// 	$args = ClientNamespace\CPT\Blog::modifyQueryWithFilters($args, [
	// 		'category' => $request->get_param(ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_CATEGORY) ?? '',
	// 		'tag' => $request->get_param(ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_TAG) ?? '',
	// 		'search' => $request->get_param(ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_SEARCH) ?? ''
	// 	]);

	// 	return $args;
	// }, 10, 2);
});