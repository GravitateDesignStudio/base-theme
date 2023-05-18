<?php
get_header();

get_template_part('components/banners/banner-default');

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

				WPUtil\Vendor\BlueprintBlocks::safe_display();

				?>
				<div class="tmpl-post-single__share-icons">
					<?php
					get_template_part('components/ui/share-icon', [
						'site' => 'twitter',
						'twitter_username' => 'test_username'
					]);

					get_template_part('components/ui/share-icon', [
						'site' => 'facebook'
					]);
					?>
				</div>
				<?php
			}

			WPUtil\Vendor\BlueprintBlocks::safe_display([
				'section' => ClientNamespace\Constants\ACF::BLOG_SETTINGS_BASE . '_blog_post_blocks_grav_blocks',
				'object' => 'option'
			]);
		}
		?>
	</div>
</main>
<?php

get_footer();
