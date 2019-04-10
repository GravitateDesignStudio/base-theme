<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
    'title' => 'Gravitate WordPress Theme'
]);

?>
<main class="main-content main-content__front-page">
	<?php
	WPUtil\Component::render('components/testing/theme-welcome');

	if (have_posts()) {
		while (have_posts()) {
			the_post();

			if (class_exists('GRAV_BLOCKS')) {
				GRAV_BLOCKS::display();
			}
		}
	}
	?>
</main>
<?php

get_footer();
