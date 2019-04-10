<?php
$image = isset($image) ? $image : '';
$opacity = isset($opacity) ? (int)$opacity : 0;
$pos_horz = isset($pos_horz) ? (int)$pos_horz : 50;
$pos_vert = isset($pos_vert) ? (int)$pos_vert : 50;

if ($image && class_exists('GRAV_BLOCKS'))
{
	?>
	<div class="banner__cover-image-container" <?php if ($opacity) { ?>data-opacity="<?php echo esc_attr($opacity); ?>"<?php } ?>>
		<?php
		Blueprint\Images::safe_image_output($image, [
			'class' => 'banner__cover-image ib__image--no-fx',
			'style' => "object-position: {$pos_horz}% {$pos_vert}%;"
		]);
		?>
	</div>
	<?php
}
