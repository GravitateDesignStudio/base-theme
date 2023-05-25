<?php
$query_authors = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $args['block_limit'] ?? 3,
    'author' => $args['block_filter_param_meta'],
]);

get_template_part('components/cards/card', 'author', [
    'posts' => $query_authors->posts,
]);