<?php
if (isset($link) && isset($text)) {
	$style = isset($style) ? trim($style) : '';
	$icon = isset($icon) ? trim($icon) : '';
	$additional_classes = $additional_classes ?? [];

	$classes = ['button'];

	if ($style) {
		$classes[] = 'button--' . esc_attr($style);
	}

	if (isset($trigger_video_modal) && $trigger_video_modal) {
		$classes[] = 'js__trigger-video-modal';
	}

	if ($icon) {
		$classes[] = 'button--has-icon';
	}

	if ($additional_classes) {
		if (is_array($additional_classes)) {
			$classes = array_merge($classes, $additional_classes);
		} else {
			$classes[] = trim($additional_classes);
		}
	}

	$svg_opts = [
		'class' => 'button__icon'
	];

	if (isset($no_svg_use)) {
		$svg_opts['no_use'] = true;
	}

	$additional_attrs = [];

	if (isset($attributes) && is_array($attributes)) {
		foreach ($attributes as $attr_name => $attr_value) {
			$additional_attrs[] = sprintf('%s="%s"', esc_attr($attr_name), esc_attr($attr_value));
		}
	}

	?>
	<a class="<?php echo esc_attr(implode(' ', $classes)); ?>" href="<?php echo esc_url($link); ?>" <?php echo esc_attr(implode(' ', $additional_attrs)); ?>>
		<?php
		if ($icon) {
			?>
			<span class="button__icon-container">
				<?php WPUtil\SVG::the_svg($icon, $svg_opts); ?>
			</span>
			<?php
		}
		?>
		<?php echo esc_html($text); ?>
	</a>
	<?php
}
