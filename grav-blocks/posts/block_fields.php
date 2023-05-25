<?php

$authors = get_users( array(
    'role__in' => array( 'author', 'editor', 'administrator' ),
) );

$author_options = array();

foreach ( $authors as $author ) {
    $author_options[ $author->ID ] = $author->display_name;
}

$block_fields = array(
	array (
		'key' => 'field_'.$block.'_title',
		'label' => 'Title',
		'name' => 'title',
		'type' => 'text',
	),
    array (
        'key' => 'field_'.$block.'_filter',
        'label' => 'Filter by',
        'name' => 'filter',
        'type' => 'select',
        'choices' => array (
            'tags' => 'Closest Match (by Related Tags and Categories)',
            'post_type' => 'Most Recent by Post Type',
            'taxonomy' => 'Most Recent by Category',
            'author' => 'Most Recent by Author',
            'featured' => 'Featured Posts',
            'custom' => 'Custom',
        ),
    ),
    array (
        'key' => 'field_'.$block.'_by_post_type',
        'label' => 'Choose Post Type',
        'name' => 'post_type',
        'type' => 'select',
        'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_filter',
                    'operator' => '==',
                    'value' => 'post_type',
                ),
            ),
        ),
        'choices' => get_post_types(array('public' => true)),
    ),
    array (
        'key' => 'field_'.$block.'_by_taxonomy',
        'label' => 'Choose Taxonomy type',
        'name' => 'taxonomy',
        'type' => 'select',
        'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_filter',
                    'operator' => '==',
                    'value' => 'taxonomy',
                ),
            ),
        ),
        'choices' => [
            'category' => 'Category',
            'post_tag' => 'Tag',
        ],
        'wrapper' => array (
            'width' => '50',
        ),
    ),
	array (
        'key' => 'field_'.$block.'_by_category',
        'label' => 'Choose Category',
        'name' => 'category',
        'type' => 'select',
        'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_by_taxonomy',
                    'operator' => '==',
                    'value' => 'category',
                ),
            ),
        ),
        'choices' => get_term_options('category'),
        'wrapper' => array (
            'width' => '50',
        ),
    ),
    array (
        'key' => 'field_'.$block.'_by_tag',
        'label' => 'Choose Tag',
        'name' => 'post_tag',
        'type' => 'select',
        'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_by_taxonomy',
                    'operator' => '==',
                    'value' => 'post_tag',
                ),
            ),
        ),
        'choices' => get_term_options('post_tag'),
        'wrapper' => array (
            'width' => '50',
        ),
    ),
	array (
        'key' => 'field_'.$block.'_by_author',
        'label' => 'Choose Author',
        'name' => 'author',
        'type' => 'select',
        'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_filter',
                    'operator' => '==',
                    'value' => 'author',
                ),
            ),
        ),
        'choices' => $author_options,
    ),
	array (
	    'key' => 'field_'.$block.'_custom',
	    'label' => 'Choose Custom',
	    'name' => 'custom',
	    'type' => 'relationship',
		'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_filter',
                    'operator' => '==',
                    'value' => 'custom',
                ),
            ),
        ),
	    'filters' => array (
	        0 => 'search',
	        1 => 'post_type',
	        2 => 'taxonomy',
	    ),
	    'return_format' => 'id',     // object | id
	),
	array (
	    'key' => 'field_'.$block.'_limit',
	    'label' => 'Limit',
	    'name' => 'limit',
	    'type' => 'number',
	    'instructions' => '0 = Unlimited',
		'conditional_logic' => array (
            array (
                array (
                    'field' => 'field_'.$block.'_filter',
                    'operator' => '!=',
                    'value' => 'custom',
                ),
            ),
        ),
	    'default_value' => '3',
	    'step' => '1',
	),
	GRAV_BLOCKS::get_link_fields(array('name' => 'view_more_link'))
);

return array (
	'label' => 'Posts',
	'name' => $block,
	'display' => 'block',
	'min' => '',
	'max' => '',
	'sub_fields' => $block_fields,
);
