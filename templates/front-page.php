<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
	'title' => 'Gravitate WordPress Theme'
]);

?>
<main class="main-content">
	<div class="tmpl-front-page">
		<?php
		WPUtil\Component::render('components/testing/theme-welcome');

		if (have_posts()) {
			while (have_posts()) {
				the_post();

				WPUtil\Vendor\BlueprintBlocks::safe_display();
			}
		}
		?>
	</div>
</main>
<?php

get_footer();
