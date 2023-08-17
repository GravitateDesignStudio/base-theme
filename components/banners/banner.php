<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$background_image_opts = ['post_id' => $post_id];
$button = $args['button'] ?? [];

if (isset($background_image)) {
	$background_image_opts['background_image'] = $background_image;
}
?>

<div class="banner banner-default bg-black">
	<?php get_template_part('components/banners/partials/banner', 'background', $background_image_opts); ?>
	<div class="banner-default__content faux-row">
		<h1 class="banner-default__title"><?= esc_html($args['title'] ?? get_the_title($post_id )); ?></h1>
		<?php the_button_formatter($button, ['class' => 'button button--primary']); ?>
	</div>
</div>
