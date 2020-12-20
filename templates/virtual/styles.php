<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
	'title' => 'Styles'
]);

$backgrounds = apply_filters('grav_block_background_colors', [], '');
$backgrounds = $backgrounds ? array_filter($backgrounds, function ($key) {
	return $key !== 'block-bg-image';
}, ARRAY_FILTER_USE_KEY) : [];


if (have_posts()) {
	while (have_posts()) {
		the_post();

		?>
		<script>
			document.addEventListener('DOMContentLoaded', function (e) {
				var colorSelect = document.querySelector('.style-testing__select--color');

				colorSelect.addEventListener('change', function (e) {
					var colorClass = e.currentTarget.selectedOptions[0].value;

					document.querySelector('#style-testing__content').setAttribute('class', colorClass);
				});
			});
		</script>
		<div id="style-testing__content">
			<section class="section-container wysiwyg">
				<div class="row section-inner">
					<div class="columns small-12">
						<?php WPUtil\Component::render('components/testing/style-testing'); ?>
					</div>
				</div>
			</section>
		</div>
		<div class="style-testing__color-selector faux-row">
			<label for="bg-color">Background Color:</label>
			<select id="bg-color" class="style-testing__select--color">
				<?php
				foreach ($backgrounds as $class => $label) {
					?>
					<option value="<?php echo esc_attr($class); ?>"
						<?php if ($class === '') { ?>selected<?php } ?>
					>
						<?php echo esc_html($label); ?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
		<?php

		Blueprint\Blocks::safe_display();
	}
}

get_footer();
