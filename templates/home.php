<?php
global $wp_query;

$post_id = get_option('page_for_posts');

get_header();

get_template_part('components/banners/banner', 'default', [
	'title' => trim(get_field('banner_title_override', $post_id)) ?: get_the_title(),
	'button' => WPUtil\Vendor\BlueprintBlocks::get_button_field_values('banner_button', $post_id),
	'post_id' => $post_id
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
