<?php
$post_id = $post_id ?? get_the_ID();
$title = $title ?? get_the_title($post_id);
$content = $content ?? Blueprint\Content::get_excerpt([
	'post_id' => $post_id
]);
$permalink = $permalink ?? get_the_permalink($post_id);

?>
<a class="card-search" href="<?php echo esc_url($permalink); ?>">
	<h3 class="card-search__title"><?php echo esc_html($title); ?></h3>
	<?php
	if ($content) {
		?>
		<div class="card-search__content">
			<?php echo $content; ?>
		</div>
		<?php
	}
	?>
</a>
