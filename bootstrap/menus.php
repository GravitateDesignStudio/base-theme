<?php
// Add menu shortcode that allows to add sitemap menu to wysiwyg
add_shortcode('menu', function ($atts, $content = null) {
	extract(shortcode_atts(['name' => null], $atts));

	return wp_nav_menu([
		'menu' => $name,
		'echo' => false
	]);
});

WPUtil\ThemeSupport::register_menus([
	ClientNamespace\Constants\Menus::DESKTOP_MENU => 'Desktop Menu',
	ClientNamespace\Constants\Menus::FOOTER_LINKS_MENU => 'Footer Links',
	ClientNamespace\Constants\Menus::FOOTER_LEGAL_MENU => 'Footer Legal',
	ClientNamespace\Constants\Menus::MOBILE_MENU => 'Mobile Menu'
]);
