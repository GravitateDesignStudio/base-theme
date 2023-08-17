<?php
if (!defined('ABSPATH')) {
	exit;
}

// check for/require composer autoloader
if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	die("The required composer dependencies must be installed for this theme. Please run 'composer install' from the theme root.");
}

require_once 'vendor/autoload.php';

$enqueue = new \WPackio\Enqueue( 'baseTheme', 'dist', '1.0.0', 'theme' );

$assets = $enqueue->register( 'app', 'main', [
	'js' => true,
	'css' => true,
	'js_dep' => [],
	'css_dep' => [],
	'in_footer' => true,
	'media' => 'all',
]);

$admin_assets = $enqueue->register( 'app', 'admin', []);

function enqueueScripts() {
	global $assets;
	foreach($assets['js'] as $js) {
		wp_enqueue_script($js['handle']);
	}

	foreach($assets['css'] as $css) {
		wp_enqueue_style($css['handle']);
	}
}

// Add editor styles to TinyMCE
foreach($admin_assets['css'] as $css) {
	add_editor_style($css['url']);
}

add_action('wp_enqueue_scripts', 'enqueueScripts');

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
require_once 'bootstrap/acf.php';
require_once 'bootstrap/menus.php';
require_once 'bootstrap/plugins.php';
require_once 'bootstrap/blocks.php';
require_once 'bootstrap/theme-settings-pages.php';
require_once 'bootstrap/api.php';
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

/*
 * creates a function to simplify the use of the corresponding WPUtil
 */
function the_svg($filename, bool $no_use = false) {
	$options = ['no_use' => $no_use ];
	WPUtil\SVG::the_svg($filename, $options);
}
function the_button_formatter($button, $params = []) {
	if (!isset($button['url']) || !isset($button['title'])) {
		return;
	};

	$all_params = array_map(function($a, $key) {
		return $key.'="'.$a.'"';
	}, $params, array_keys($params));
	
	echo '<a '.implode(" " ,$all_params).' href="'.esc_url($button['url']).'" target="'.$button['target'].'">'.$button['title'].'</a>';
}

// add support for page excerpts
add_post_type_support( 'page', 'excerpt' );
add_filter( 'excerpt_length', fn() => 15, 999 );
add_filter('excerpt_more', fn() => '&hellip;');
