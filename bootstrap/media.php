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

// add ImageBuddy 'ib-sources' values to background images
add_filter('grav_blocks_background_image_attributes', function ($bg_image_attrs, $block_attrs, $acf_image_object) {
	$sizes = Blueprint\Images::get_image_sizes_from_acf_object($acf_image_object, [
		'ignore_cropped' => true
	]);

	if (!$sizes) {
		return $bg_image_attrs;
	}

	$used_urls = [];
	$ib_sources = [];

	foreach ($sizes as $size) {
		if (in_array($size['url'], $used_urls, true)) {
			continue;
		}

		$used_urls[] = $size['url'];
		$ib_sources[] = implode(' ', array($size['url'], $size['width'], $size['height']));
	}

	$bg_image_attrs['style'] = 'background-image: url(data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==);';
	$bg_image_attrs['data-ib-sources'] = '"' . implode(', ', $ib_sources) . '"';
	$bg_image_attrs['data-ib-match-dpr'] = '"0"';

	return $bg_image_attrs;
}, 10, 3);

// add ImageBuddy 'ib-sources' values to images
add_filter('grav_blocks_image_tag', function ($default_markup, $tag, $attributes, $acf_image_object) {
	if ($tag !== 'img') {
		return $default_markup;
	}

	$sizes = Blueprint\Images::get_image_sizes_from_acf_object($acf_image_object, [
		'ignore_cropped' => true
	]);

	if (!$sizes) {
		return $default_markup;
	}

	$used_urls = array();
	$ib_sources = array();

	foreach ($sizes as $size) {
		if (in_array($size['url'], $used_urls, true)) {
			continue;
		}

		$used_urls[] = $size['url'];
		$ib_sources[] = implode(' ', array($size['url'], $size['width'], $size['height']));
	}

	// $attributes['src'] = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='; // 1x1
	$attributes['src'] = '"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAABCAQAAACC0sM2AAAADElEQVR42mNkGCYAAAGSAAIVQ4IOAAAAAElFTkSuQmCC"'; // 100x1
	$attributes['data-ib-sources'] = '"' . implode(', ', $ib_sources) . '"';
	$attributes['data-ib-match-dpr'] = '"0"';

	$attributes_str = trim(urldecode(http_build_query($attributes, '', ' ')));

	return "<img {$attributes_str} />";
}, 10, 4);

add_filter('acf/load_value/type=wysiwyg', function ($value, $post_id, $field) {
	return \Blueprint\Images::replace_content_with_ib_images($value);
}, 10, 3);

add_filter('the_content', function ($content) {
	return \Blueprint\Images::replace_content_with_ib_images($content);
});
