<?php
$post_id = isset($post_id) && is_int($post_id) ? $post_id : get_the_ID();
$category = isset($category) && is_string($category) ? $category : '';
$title = isset($title) && is_string($title) ? $title : get_the_title($post_id);
$content = isset($content) && is_string($content) ? $content : Blueprint\Content::get_excerpt();
$permalink = isset($permalink) && is_string($permalink) ? $permalink : get_the_permalink($post_id);
$image = $image ?? get_post_thumbnail_id($post_id);

if (!$image && isset($default_image)) {
	$image = $default_image;
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
	<div class="card-blog__content-container">
		<h6 class="card-blog__category"><?php echo esc_html($category); ?></h6>
		<h3 class="card-blog__title"><?php echo esc_html($title); ?></h3>
		<div class="card-blog__content">
			<?php
			// phpcs:ignore
			echo $content;
			?>
		</div>
	</div>
</a>
