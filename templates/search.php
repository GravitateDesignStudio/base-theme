<?php
get_header();

get_template_part('components/banners/banner', 'default', [
	'title' => 'Search Results'
]);

?>
<main class="tmpl-search">
	<div class="row align-center">
		<div class="columns small-12 large-10">
			<?php
			global $wp_query;

			get_template_part('components/posts-list/posts-list', 'search', [
				'wp_query_obj' => $wp_query
			]);
			?>
		</div>
	</div>
</main>
<?php

WPUtil\Vendor\BlueprintBlocks::safe_display([
	'section' => ClientNamespace\Constants\ACF::THEME_OPTIONS_SEARCH_BASE . '_search_archive_blocks_grav_blocks',
	'object' => 'option'
]);

get_footer();
