<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<?php do_action('global_head_top_content'); ?>
	<?php if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) { the_field('global_head_top_content', 'option', false); } ?>
	
	<title><?php bloginfo("name"); ?> <?php wp_title('&bull;'); ?></title>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="application-name" content="<?php bloginfo('name'); ?>">

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php /* TODO: generate a favicon at https://realfavicongenerator.net */ ?>
	
	<?php wp_head(); ?>
	<?php if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) { the_field('global_head_bottom_content', 'option', false); } ?>
</head>
<body id="body" <?php body_class(); ?>>
	<?php if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) { the_field('global_body_top_content', 'option', false); } ?>
	
	<div class="global-wrapper">
		<?php Grav\WP\Content::get_template_part('components/header'); ?>
	
		<main class="global-content">
