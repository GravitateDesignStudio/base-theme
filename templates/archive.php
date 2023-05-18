<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
	'title' => Blueprint\Archives::get_title()
]); ?>

<main class="main-content">
		<?php if (have_posts()) :
			?>
			<div class="row archive__cards-container">
				<?php
				while (have_posts())
				{
					?>
					<div class="columns small-12 large-10 offset-large-1">
						<?php the_post() ?>
						<?php WPUtil\Component::render('components/cards/card-search') ?>
					</div>
					<?php
				}
				?>
			</div>
			<?php WPUtil\Component::render('components/archive/navigation') ?>
		<?php else : ?>
			<p>No posts were found</p>
		<?php endif	?>
</main>

<?php get_footer();
