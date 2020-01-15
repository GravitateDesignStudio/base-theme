<?php
get_header();

WPUtil\Component::render('components/banners/banner-default');

?>
<main class="main-content">
	<div class="tmpl-<?php echo esc_attr(get_post_type()); ?>">
		<?php
		if (have_posts()) {
			while (have_posts()) {
				the_post();

				if (get_the_content()) {
					?>
					<section class="block-container">
						<div class="block-inner">
							<div class="row align-center">
								<div class="columns small-12 large-10 wysiwyg">
									<?php the_content(); ?>
								</div>
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
		?>
	</div>
</main>
<?php

get_footer();
