<?php
$post_id = isset($post_id) ? $post_id : get_the_ID();
$background_image_opts = ['post_id' => $post_id];

if (isset($background_image)) {
	$background_image_opts['background_image'] = $background_image;
}
?>

<div class="banner banner-default bg-black">
	<?php WPUtil\Component::render('components/banners/partials/banner-background', $background_image_opts); ?>
	<div class="banner-default__content faux-row">
		<h1 class="banner-default__title"><?php echo esc_html($args['title']); ?></h1>
		<?php if ($args['button']->link && $args['button']->text) : ?>
			<a href="<?php echo esc_url($args['button']->link); ?>" class="button banner-default__button"><?php echo esc_html($args['button']->text); ?></a>
		<?php endif; ?>
	</div>
</div>
