<?php
get_header();

$blog_page_id = get_option('page_for_posts');

get_template_part('components/banners/banner', 'default', [
	'title' => get_the_title($blog_page_id),
	'post_id' => $blog_page_id
]);

?>
<main class="tmpl-archive tmpl-archive--blog">
	<?php
	global $wp_query;

	get_template_part('components/posts-list/posts-list', 'blog', [
		'wp_query_obj' => ($wp_query && is_a($wp_query, 'WP_Query')) ? $wp_query : false,
	]);
	?>
</main>
<?php

WPUtil\Vendor\BlueprintBlocks::safe_display([
	'section' => ClientNamespace\Constants\ACF::BLOG_SETTINGS_BASE . '_blog_archive_blocks_grav_blocks',
	'object' => 'option'
]);

get_footer();
