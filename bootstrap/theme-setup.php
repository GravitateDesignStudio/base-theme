<?php
WPUtil\Templates::use_path_for_templates('templates');

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
WPUtil\Content::filter_p_tags_on_images();

WPUtil\Head::cleanup();
// WPUtil\Head::remove_rss_feed_links();

WPUtil\Util::autoflush_rewrite_rules();

WPUtil\ThemeSupport::automatic_feed_links();

WPUtil\ThemeSupport::custom_logo();

// The WP CSS customizer is the devil
add_action('customize_register', function ($wp_customize) {
	$wp_customize->remove_section('custom_css');
});
