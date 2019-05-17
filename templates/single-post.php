<?php
get_header();

WPUtil\Component::render('components/banners/banner-default');

?>
<main class="main-content">
	<div class="tmpl-post-single">
		<?php
		if (have_posts())
		{
			while (have_posts())
			{
				the_post();

				if (get_the_content())
				{
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

				?>
				<div class="tmpl-post-single__share-icons">
					<?php
					WPUtil\Component::render('components/ui/share-icon', [
						'site' => 'twitter',
						'twitter_username' => 'test_username'
					]);

					WPUtil\Component::render('components/ui/share-icon', [
						'site' => 'facebook'
					]);
					?>
				</div>
				<?php
			}

			GRAV_BLOCKS::display([
				'section' => 'blog_settings_blog_post_blocks_grav_blocks',
				'object' => 'option'
			]);
		}
		?>
	</div>
</main>
<?php

get_footer();
