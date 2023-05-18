<?php

$post_id = $args['post_id'] ?? get_the_ID();
$banner_type = $args['banner_type'] ?? get_field('banner_background_type', $post_id);

if ($banner_type === 'video') {
	get_template_part('components/banners/partials/banner-cover', 'video', ['post_id' => $post_id]);
} else {
	$background_image = $args['background_image'] ?? get_field('banner_background_image', $post_id);
	
	if (!$background_image) {
		$background_image = acf_get_attachment(get_post_thumbnail_id($post_id));
	}

	get_template_part('components/banners/partials/banner-cover', 'image', [
		'image' => $background_image,
		'pos_horz' => get_field('banner_background_image_pos_horz', $post_id) ?? 50,
		'pos_vert' => get_field('banner_background_image_pos_vert', $post_id) ?? 50
	]);
}