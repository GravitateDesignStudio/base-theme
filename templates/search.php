<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
	'title' => 'Search Results'
]);

?>
<main class="main-content">
	<div class="tmpl-search">
		<?php
		if (have_posts())
		{
			global $wp_query;
			global $paged;

			$total_pages = (int)$wp_query->max_num_pages;
			$posts_per_page = (int)$wp_query->query_vars['posts_per_page'];

			?>
			<div class="archive__cards-container"
				data-load-more-target
				data-current-page="<?php echo $paged + 1; ?>"
				data-total-pages="<?php echo $total_pages; ?>"
				data-posts-per-page="<?php echo $posts_per_page; ?>"
				data-search="<?php echo get_search_query(); ?>"
				>
				<?php
				while (have_posts())
				{
					?>
					<div class="row align-center">
						<div class="columns small-12 large-10">
							<?php
							the_post();

							WPUtil\Component::render('components/cards/card-search');
							?>
						</div>
					</div>
					<?php
				}
			?>
			</div>
			<?php

			WPUtil\Component::render('components/archive/load-more');
		}
		else
		{
			?>
			<section class="block-container">
				<div class="block-inner">
					<div class="row align-center">
						<div class="columns small-12 large-10">
							No search results found
						</div>
					</div>
				</div>
			</section>
			<?php
		}
		?>
	</div>
</main>
<?php

get_footer();
