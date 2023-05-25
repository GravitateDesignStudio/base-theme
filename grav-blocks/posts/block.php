<?php

$block_title = $block_title ?? get_sub_field('title');
$block_filter = $block_filter ?? get_sub_field('filter');
$block_filter_param = $block_filter_param ?? get_sub_field($block_filter);
$block_filter_param_meta = $block_filter_param_meta ?? get_sub_field($block_filter_param);

$block_limit = $block_limit ?? get_sub_field('limit');
$block_view_more_link_type = isset($block_view_more_link) ? $block_view_more_link : get_sub_field('view_more_link_type');
$block_view_more_link_text = isset($block_view_more_text) ? $block_view_more_text : get_sub_field('view_more_link_text');
$block_view_more_link_url = isset($block_view_more_link_url) ? $block_view_more_link_url : get_sub_field('view_more_link_' . $block_view_more_link_type);
?>

<div class="block-inner">
	<img class="block-posts__accent-graphic" src="<?= get_stylesheet_directory_uri() ?>/media/images/bg-accent-graphic.png" alt="">
	<div class="column small-12 medium-9">
		<?php if (get_sub_field('title')) : ?>
			<h2><?php the_sub_field('title') ?></h2>
		<?php endif; ?>
		
	</div>
	<?php

	$args = [
		'block_limit' => $block_limit ?? 3,
		'block_filter_param' => $block_filter_param ?? [],
		'block_filter_param_meta' => $block_filter_param_meta ?? [],
		'unique_item_class' => $unique_item_class ?? '',
	];
	 get_template_part('grav-blocks/posts/parts/post', $block_filter, $args) ?>
</div>