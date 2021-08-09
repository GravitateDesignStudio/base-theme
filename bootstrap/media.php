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

// add ImageBuddy 'data-ib-sources' values to background images
add_filter('grav_blocks_background_image_attributes', function ($bg_image_attrs, $block_attrs, $acf_image_object) {
	$sizes = WPUtil\Images::get_image_sizes_from_acf_object($acf_image_object, [
		'ignore_cropped' => true
	]);

	if (!$sizes) {
		return $bg_image_attrs;
	}

	$bg_image_attrs['style'] = 'background-image: url(data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==);';
	$bg_image_attrs['data-ib-sources'] = '"' . WPUtil\Images::build_ib_sources_string($sizes) . '"';
	$bg_image_attrs['data-ib-match-dpr'] = '"0"';

	return $bg_image_attrs;
}, 10, 3);

// add ImageBuddy 'data-ib-sources' values to images
add_filter('grav_blocks_image_tag', function ($default_markup, $tag, $attributes, $acf_image_object) {
	if ($tag !== 'img') {
		return $default_markup;
	}

	$sizes = WPUtil\Images::get_image_sizes_from_acf_object($acf_image_object, [
		'ignore_cropped' => true
	]);

	if (!$sizes) {
		return $default_markup;
	}

	$attributes['src'] = '"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAABCAQAAACC0sM2AAAADElEQVR42mNkGCYAAAGSAAIVQ4IOAAAAAElFTkSuQmCC"'; // 100x1
	$attributes['data-ib-sources'] = '"' . WPUtil\Images::build_ib_sources_string($sizes) . '"';
	$attributes['data-ib-match-dpr'] = '"0"';

	$attributes_str = trim(urldecode(http_build_query($attributes, '', ' ')));

	return "<img {$attributes_str} />";
}, 10, 4);

// add ImageBuddy 'data-ib-sources' values to ACF wysiwyg fields when they are loaded
add_filter('acf/load_value/type=wysiwyg', function ($value, $post_id, $field) {
	return is_admin() ? $value : \WPUtil\Images::replace_content_with_ib_images($value);
}, 10, 3);

// add ImageBuddy 'data-ib-sources' values to post content when it is loaded
add_filter('the_content', function ($content) {
	return is_admin() ? $content : \WPUtil\Images::replace_content_with_ib_images($content);
});
