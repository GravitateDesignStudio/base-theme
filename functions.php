<?php
if (!defined('ABSPATH')) {
	exit;
}

// check for/require composer autoloader
if (!file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
	die("The required composer dependencies must be installed for this theme. Please run 'composer install' from the theme root.");
}

require_once 'vendor/autoload.php';

// check for existence of dfrei/wp-util package
if (!class_exists('\WPUtil\Content')) {
	die("The 'dfrei/wp-util' composer package is required for this theme. Please run 'composer install' from the theme root.");
}

// redirect image URLs for local development
if (defined('LOCAL_IMAGE_REDIRECT') && WP_HOME !== null && stripos(WP_HOME, '.local.com') !== -1) {
	WPUtil\Dev\Image::local_image_redirect(LOCAL_IMAGE_REDIRECT);
}

require_once 'bootstrap/theme-setup.php';
require_once 'bootstrap/performance.php';
require_once 'bootstrap/media.php';
require_once 'bootstrap/scripts-styles.php';
require_once 'bootstrap/custom-post-types.php';
require_once 'bootstrap/taxonomies.php';
require_once 'bootstrap/acf.php';
require_once 'bootstrap/menus.php';
require_once 'bootstrap/tinymce.php';
require_once 'bootstrap/plugins.php';
require_once 'bootstrap/blocks.php';
require_once 'bootstrap/theme-settings-pages.php';
require_once 'bootstrap/api.php';

add_filter('grav_blocks_output_default_styles', function($output_default_styles) {
	return false;
});

// add_filter('grav_blocks_output_default_js', function($output_default_js) {
// 	return false;	
// });

add_filter('grav_block_use_default_css', function($use_default_css, $block_name) {
	if ($block_name === 'columns') {
		return false;
	}

	return $use_default_css;
}, 10, 2);

add_filter('grav_block_use_default_js', function($use_default_js, $block_name) {
	if ($block_name === 'columns') {
		return false;
	}

	return $use_default_js;
}, 10, 2);

add_filter('grav_blocks_title_guidelines_title', function($title) {
	return 'changed the title';
});

add_filter('grav_blocks_title_guidelines_text', function($text) {
	return 'testing the guidelines text for title blocks';
});
