<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
	'title' => Blueprint\Archives::get_title()
]);

?>
<main class="main-content">
	<div class="tmpl-archive">
		<?php
		if (have_posts())
		{
			?>
			<div class="row align-center archive__cards-container">
				<?php
				while (have_posts())
				{
					?>
					<div class="columns small-12 large-10">
						<?php
						the_post();

						WPUtil\Component::render('components/cards/card-search');
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php

			WPUtil\Component::render('components/archive/navigation');
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
