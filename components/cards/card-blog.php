<?php
$post_id = $post_id ?? get_the_ID();
$title = $title ?? get_the_title($post_id);
$content = $content ?? Blueprint\Content::get_excerpt();
$permalink = $permalink ?? get_the_permalink($post_id);
$image = $image ?? get_post_thumbnail_id($post_id);

if (!$image) {
	$image = get_field('blog_settings_default_featured_image', 'option');
}

?>
<a class="card-blog" href="<?php echo esc_url($permalink); ?>">
	<?php
	if ($image) {
		?>
		<div class="card-blog__image-container">
			<?php Blueprint\Images::safe_image_output($image, ['class' => 'card-blog__image']); ?>
		</div>
		<?php
	}
	?>
	<h3 class="card-blog__title"><?php echo esc_html($title); ?></h3>
	<div class="card-blog__content">
		<?php
		// phpcs:ignore
		echo $content;
		?>
	</div>
</a>
