<?php

/**
 * Template Name: Resources
 */

get_header();

get_template_part('components/banners/banner', 'default');

?>
<main class="main-content">
	<?php // WPUtil\Vendor\BlueprintBlocks::safe_display() ?>

	<?php
	$resources_query = new WP_Query(array(
		'post_type' => 'resource',
		'posts_per_page' => 12,
	));

	get_template_part('components/posts-list/posts-list', 'blog', [
		'wp_query_object' => $resources_query,
	]);
	?>

</main>
<?php

get_footer();
