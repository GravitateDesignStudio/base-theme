<?php
$tax_query = new WP_Query([
    'post_type' => 'any',
    'posts_per_page' => $args['block_limit'] ?? 3,
    'tax_query' => array(
        array(
            'taxonomy' => $args['block_filter_param'],
            'field'    => 'slug',
            'terms'    => $args['block_filter_param_meta'],
        ),
    )
]);

get_template_part('components/cards/card', $args['block_filter_param'], [
    'posts' => $tax_query->posts,
]);