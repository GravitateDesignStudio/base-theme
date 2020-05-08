<?php
$post_id = isset($post_id) ? $post_id : get_the_ID();

if (!isset($title)) {
	$title_override = get_field('banner_title_override', $post_id);
	$title = trim($title_override) ? trim($title_override) : get_the_title($post_id);
}

$button = Blueprint\Blocks::get_button_field_values('banner_button', $post_id);

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
		if ($button->link && $button->text)
		{
			?>
			<a href="<?php echo esc_url($button->link); ?>" class="button banner-default__button"><?php echo esc_html($button->text); ?></a>
			<?php
		}
		?>
	</div>
</div>
