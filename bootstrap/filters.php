<?php

// Override the title
add_filter('the_title', function($title, $post_id) {
    return trim(get_field('banner_title_override', $post_id)) ?: $title;
}, 10, 2);
