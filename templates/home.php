<?php
get_header();

$blog_page_id = get_option('page_for_posts');

WPUtil\Component::render('components/banners/banner-default', [
	'title' => get_the_title($blog_page_id),
	'post_id' => $blog_page_id
]);

?>
<main class="main-content">
	<div class="tmpl-archive-blog">
		<?php
		if (have_posts())
		{
			global $wp_query;
			global $paged;

			$total_pages = (int)$wp_query->max_num_pages;
			$posts_per_page = (int)$wp_query->query_vars['posts_per_page'];

			?>
			<div class="row small-up-1 medium-up-2 large-up-3 archive__cards-container"
				data-load-more-target
				data-current-page="<?php echo esc_attr($paged + 1); ?>"
				data-total-pages="<?php echo esc_attr($total_pages); ?>"
				data-posts-per-page="<?php echo esc_attr($posts_per_page); ?>"
				>
				<?php
				while (have_posts())
				{
					?>
					<div class="columns">
						<?php
						the_post();

						WPUtil\Component::render('components/cards/card-blog');
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php

			WPUtil\Component::render('components/archive/load-more');

			WPUtil\Vendor\BlueprintBlocks::safe_display([
				'object' => $blog_page_id
			]);
		}
		else
		{
			?>
			<p>No posts were found</p>
			<?php
		}
		?>
	</div>
</main>
<?php

get_footer();
