<?php
/*
*	Template Name: Styles
*/
get_header();

Grav\WP\Content::get_template_part('components/banners/banner-default');

if (have_posts()) {
	while (have_posts()) {
		the_post();

		$bg_colors = ['bg-black', 'bg-blue', 'bg-red', 'bg-gray'];

		?>
		<section class="section-container wysiwyg">
			<div class="row section-inner">
				<div class="columns small-12">
					<?php Grav\WP\Content::get_template_part('components/testing/style-testing'); ?>
				</div>
			</div>
		</section>
		<?php

		foreach ($bg_colors as $bg_color)
		{
			?>
			<section class="section-container <?php echo esc_attr($bg_color); ?> wysiwyg">
				<div class="row section-inner">
					<div class="columns small-12">
						<?php Grav\WP\Content::get_template_part('components/testing/style-testing'); ?>
					</div>
				</div>
			</section>
			<?php
		}

		if (class_exists('GRAV_BLOCKS')) {
			GRAV_BLOCKS::display();
		}
	}
}

get_footer();
