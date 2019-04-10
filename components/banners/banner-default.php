<?php
$post_id = isset($post_id) ? $post_id : get_the_ID();
$title = isset($title) ? $title : get_the_title($post_id);

if ($title_override = get_field('banner_title_override', $post_id)) {
	$title = $title_override;
}

$button_type = get_field('banner_button_type', $post_id);
$button_text = '';
$button_link = '';

if ($button_type !== 'none') {
	$button_text = get_field('banner_button_text', $post_id);
	$button_link = get_field('banner_button_'.$button_type, $post_id);
}

$background_image_opts = ['post_id' => $post_id];

if (isset($background_image)) {
	$background_image_opts['background_image'] = $background_image;
}

?>
<div class="banner banner-default bg-black">
	<?php WPUtil\Component::render('components/banners/partials/banner-background', $background_image_opts); ?>
	<div class="banner-default__content">
		<h1 class="banner-default__title"><?php echo esc_html($title); ?></h1>
		<?php
		if ($button_text && $button_link)
		{
			?>
			<a href="<?php echo esc_url($button_link); ?>" class="button banner-default__button"><?php echo esc_html($button_text); ?></a>
			<?php
		}
		?>
	</div>
</div>
