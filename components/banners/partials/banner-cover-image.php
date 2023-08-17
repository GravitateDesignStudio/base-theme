<?php
$pos_horz = isset($args['pos_horz']) ? (int)$args['pos_horz'] : 50;
$pos_vert = isset($args['pos_vert']) ? (int)$args['pos_vert'] : 50;

if ($args['image'] && class_exists('GRAV_BLOCKS')) : ?>

	<div class="banner__cover-image-container" 
	<?php if ($args['opacity']) : ?>
		data-opacity="<?= esc_attr($args['opacity']); ?>"
	<?php endif; ?>
	>
		<?php
		Blueprint\Images::safe_image_output($args['image'], [
			'class' => 'banner__cover-image ib__image--no-fx',
			'style' => "object-position: {$pos_horz}% {$pos_vert}%;"
		]);
		?>
	</div>

<?php endif;