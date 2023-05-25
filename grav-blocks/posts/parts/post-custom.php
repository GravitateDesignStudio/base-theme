<?php
$custom_query = new WP_Query([
    'post__in' => $args['block_filter_param'],
    'orderby' => 'post__in',
]);

get_template_part('components/cards/card', 'post', [
    'posts' => $custom_query->posts,
]);
