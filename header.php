<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<?php
	do_action('global_head_top_content');

	if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) {
		the_field('global_head_top_content', 'option', false);
	}

	?>
	<title><?php bloginfo('name'); ?> <?php wp_title('&bull;'); ?></title>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="application-name" content="<?php bloginfo('name'); ?>">

	<?php /* TODO: generate a favicon at https://realfavicongenerator.net */ ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php
	// polyfills for IE users
	// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
	// phpcs:ignore
	if (isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'trident/') !== false) {
		?>
		<script crossorigin="anonymous" src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
		<?php
	}
	// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

	wp_head();

	if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) {
		the_field('global_head_bottom_content', 'option', false);
	}
	?>
</head>
<body id="body" <?php body_class(); ?>>
	<?php
	if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) {
		the_field('global_body_top_content', 'option', false);
	}

	WPUtil\Component::render('components/header');
