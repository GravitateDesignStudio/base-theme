<?php
$post_id = $post_id ?? get_the_ID();
$video_poster_image = get_field('banner_background_video_poster', $post_id);

$data_sizes = [
	'mobile' => [
		'video_url' => get_field('banner_background_video_url_mobile'),
		'poster_url' => $video_poster_image ? $video_poster_image['sizes']['medium'] : ''
	],
	'tablet' => [
		'video_url' => get_field('banner_background_video_url_tablet'),
		'poster_url' => $video_poster_image ? $video_poster_image['sizes']['medium_large'] : ''
	],
	'desktop' => [
		'video_url' => get_field('banner_background_video_url_desktop'),
		'poster_url' => $video_poster_image ? $video_poster_image['sizes']['large'] : ''
	],
];

?>
<div class="banner__cover-video-container">
	<video class="banner__cover-video"
		data-video-mobile="<?php echo esc_attr($data_sizes['mobile']['video_url']); ?>"
		data-video-tablet="<?php echo esc_attr($data_sizes['tablet']['video_url']); ?>"
		data-video-desktop="<?php echo esc_attr($data_sizes['desktop']['video_url']); ?>"
		data-poster-mobile="<?php echo esc_attr($data_sizes['mobile']['poster_url']); ?>"
		data-poster-tablet="<?php echo esc_attr($data_sizes['tablet']['poster_url']); ?>"
		data-poster-desktop="<?php echo esc_attr($data_sizes['desktop']['poster_url']); ?>"
		autoplay muted loop playsinline preload="auto">
	</video>
</div>
