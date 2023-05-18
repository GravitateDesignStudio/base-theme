<?php

// Override the title
add_filter('the_title', function($title, $post_id) {
	if( $title_override = get_field('banner_title_override', $post_id) ) {
        $title = trim($title_override) ? trim($title_override) : get_the_title($post_id);
    }
    
    return $title;
}, 10, 2);
