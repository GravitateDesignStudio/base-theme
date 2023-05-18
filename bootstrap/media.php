<?php
// set JPEG compression quality
add_filter('jpeg_quality', function () {
	return 75;
});

// enable media library support for SVGs
WPUtil\Media::add_upload_mime_types([
	'svg' => 'image/svg+xml'
]);

// enable post thumbnail support
WPUtil\ThemeSupport::post_thumbnails(300, 300, true);

// set image sizes
WPUtil\ThemeSupport::image_sizes([
	'small' => [
		'width' => 300,
		'height' => 300,
		'crop' => false
	],
	'xlarge' => [
		'width' => 1440,
		'height' => 1900,
		'crop' => false
	]
]);
