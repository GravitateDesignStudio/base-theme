<?php
if (!defined('ABSPATH')) {
	exit;
}

// check for/require composer autoloader
if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
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
require_once 'bootstrap/hello-bar.php';
require_once 'bootstrap/virtual-pages.php';
require_once 'bootstrap/pre-get-posts.php';
require_once 'bootstrap/filters.php';

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false', 10 );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action( 'wp_enqueue_scripts', function() {
    // Remove CSS on the front end.
    wp_dequeue_style( 'wp-block-library' );

    // Remove Gutenberg theme.
    wp_dequeue_style( 'wp-block-library-theme' );

    // Remove inline global CSS on the front end.
    wp_dequeue_style( 'global-styles' );
}, 20 );