<?php
$post_id = isset($post_id) && is_int($post_id) ? $post_id : get_the_ID();
$title = isset($title) && is_string($title) ? $title : get_the_title($post_id);
$content = isset($content) && is_string($content) ? $content : Blueprint\Content::get_excerpt([
	'post_id' => $post_id
]);
$permalink = isset($permalink) && is_string($permalink) ? $permalink : get_the_permalink($post_id);

?>
<a class="card-search" href="<?php echo esc_url($permalink); ?>">
	<h3 class="card-search__title"><?php echo esc_html($title); ?></h3>
	<?php
	if ($content) {
		?>
		<div class="card-search__content">
			<?php
			// phpcs:ignore
			echo $content;
			?>
		</div>
		<?php
	}
	?>
</a>
