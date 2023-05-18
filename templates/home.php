<?php
global $wp_query;

$blog_page_id = get_option('page_for_posts');

get_header();

get_template_part('components/banners/banner', 'default', [
	'title' => get_the_title($blog_page_id),
	'button' => WPUtil\Vendor\BlueprintBlocks::get_button_field_values('banner_button', $blog_page_id),
	'post_id' => $blog_page_id
]);
?>

<main class="tmpl-archive tmpl-archive--blog">
	<?php
	get_template_part('components/posts-list/posts-list', 'blog', [
		'wp_query_obj' => $wp_query
	]);
	?>
</main>

<?php
WPUtil\Vendor\BlueprintBlocks::safe_display([
	'section' => ClientNamespace\Constants\ACF::BLOG_SETTINGS_BASE . '_blog_archive_blocks_grav_blocks',
	'object' => 'option'
]);

get_footer();
