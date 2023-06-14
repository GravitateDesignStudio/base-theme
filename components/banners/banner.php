<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$background_image_opts = ['post_id' => $post_id];

$title = isset($args['title']) ? $args['title'] : get_the_title();

if (isset($background_image)) {
	$background_image_opts['background_image'] = $background_image;
}
?>

<div class="banner banner-default bg-black">
	<?php get_template_part('components/banners/partials/banner', 'background', $background_image_opts); ?>
	<div class="banner-default__content faux-row">
		<h1 class="banner-default__title"><?= esc_html( $title ) ?></h1>
		<?php if ( isset($args['button'] )) : ?>
			<a href="<?= esc_url($args['button']->link); ?>" class="button banner-default__button"><?= esc_html($args['button']->text); ?></a>
		<?php endif; ?>
	</div>
</div>
