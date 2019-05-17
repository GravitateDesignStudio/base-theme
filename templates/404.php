<?php
get_header();

$title = get_field('theme_options_404_title', 'option');
$content = get_field('theme_options_404_content', 'option');

if (!$title) {
	$title = '404 - Not Found';
}

WPUtil\Component::render('components/banners/banner-default', [
	'title' => $title
]);

?>
<main class="main-content">
	<div class="tmpl-404">
		<?php
		if ($content)
		{
			?>
			<section class="block-container">
				<div class="block-inner">
					<div class="row">
						<div class="columns small-12 large-10 wysiwyg">
							<?php echo $content; ?>
						</div>
					</div>
				</div>
			</section>
			<?php
		}

		GRAV_BLOCKS::display(array(
			'section' => '404_blocks_grav_blocks',
			'object' => 'option'
		));
		?>
	</div>
</main>
<?php

get_footer();
