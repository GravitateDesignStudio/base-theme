<?php
namespace WPUtil;

use WPUtil\{ StaticCache, Util };
use WPUtil\Models\{ ImageInfo, ImageSize };
use DOMDocument;

abstract class Images
{
	/**
	 * Use imagick for processing images if available
	 *
	 * @return boolean
	 */
	public static function use_imagick_if_available(): bool
	{
		if (!class_exists('Imagick')) {
			return false;
		}

		add_filter('wp_image_editors', function() {
			return array('WP_Image_Editor_Imagick');
		});

		return true;
	}

	/**
	 * Return a key/values array of currently registered image sizes. The values
	 * will contain the following keys: 'width', 'height', and 'crop'.
	 *
	 * @return array<string, \WPUtil\Models\ImageSize>
	 */
	public static function get_image_sizes(): array
	{
		$cached_value = StaticCache::get('image_sizes');

		if ($cached_value !== null) {
			return $cached_value;
		}

		global $_wp_additional_image_sizes;

		$sizes = [];

		foreach (get_intermediate_image_sizes() as $size) {
			$width = 0;
			$height = 0;
			$crop = false;

			if (in_array($size, ['thumbnail', 'medium', 'medium_large', 'large'], true)) {
				$width = get_option("{$size}_size_w");
				$height = get_option("{$size}_size_h");
				$crop = (bool)get_option("{$size}_crop");
			} else if (isset($_wp_additional_image_sizes[$size])) {
				$width = $_wp_additional_image_sizes[$size]['width'];
				$height = $_wp_additional_image_sizes[$size]['height'];
				$crop = $_wp_additional_image_sizes[$size]['crop'];
			}

			$sizes[$size] = new ImageSize($width, $height, $crop);
		}

		StaticCache::set('image_sizes', $sizes);

		return StaticCache::get('image_sizes');
	}

	/**
	 * Check if the specified image size is cropped
	 *
	 * @param string $size_name
	 * @return boolean
	 */
	public static function is_cropped_size(string $size_name): bool
	{
		$sizes = self::get_image_sizes();

		// check for size name key in sizes array
		if (!isset($sizes[$size_name])) {
			return false;
		}

		return $sizes[$size_name]->crop;
	}

	/**
	 * Return an array of image size information from an ACF object. The returned
	 * array will be a key/value pair with the size name as the key and an
	 * ImageInfo object as the value.
	 *
	 * Valid options include:
	 * 'ignore_cropped' - Cropped image sizes will be ignored if this is set to a truthy value
	 *
	 * @param int|array $acf_image
	 * @param array $opts
	 * @return array<string, \WPUtil\Models\ImageInfo>
	 */
	public static function get_image_sizes_from_acf_object($acf_image, array $opts = []): array
	{
		if (is_numeric($acf_image) && function_exists('acf_get_attachment')) {
			$acf_image = acf_get_attachment($acf_image);
		}

		if (!is_array($acf_image)) {
			return [];
		}

		$acf_image_sizes = [];

		if (isset($acf_image['sizes']) && is_array($acf_image['sizes'])) {
			foreach ($acf_image['sizes'] as $size => $url) {
				// not a url value
				if (stripos($size, '-width') !== false || stripos($size, '-height') !== false) {
					continue;
				}

				// check for cropped size ignore
				if (isset($opts['ignore_cropped']) && $opts['ignore_cropped'] && self::is_cropped_size($size)) {
					continue;
				}

				$acf_image_sizes[$size] = new ImageInfo(
					$url,
					$acf_image['sizes'][$size . '-width'],
					$acf_image['sizes'][$size . '-height']
				);
			}
		}

		$acf_image_sizes['full'] = new ImageInfo(
			$acf_image['url'],
			$acf_image['width'],
			$acf_image['height']
		);

		return $acf_image_sizes;
	}

	/**
	 * Build the "data-ib-sources" attribute from an array of ImageInfo objects
	 *
	 * @param array<\WPUtil\Models\ImageInfo> $images
	 * @return string
	 */
	public static function build_ib_sources_string(array $images): string
	{
		$used_urls = [];
		$ib_sources = [];

		foreach ($images as $image) {
			if (!($image instanceof ImageInfo)) {
				continue;
			}

			if (in_array($image->url, $used_urls, true)) {
				continue;
			}

			$used_urls[] = $image->url;
			$ib_sources[] = implode(' ', array($image->url, $image->width, $image->height));
		}

		return implode(', ', $ib_sources);
	}

	/**
	 * Replaces image tags within content with versions that contain "data-ib-sources"
	 * strings. This will only work for images where the attachment ID can be determined
	 * with a "wp-image-*" class or a successful ID lookup using "attachment_url_to_postid".
	 *
	 * @param string $content
	 * @param array<string, mixed> $opts
	 * @return string
	 */
	public static function replace_content_with_ib_images(string $content, array $opts = []): string
	{
		$settings = array_merge(
			[
				'add_attributes' => [],
				'keep_width_height_attributes' => true
			],
			$opts
		);

		$matches = [];
		preg_match_all('(<img\ .+\/\>)', $content, $matches);

		if (!$matches || !$matches[0]) {
			return $content;
		}

		$tag_matches = $matches[0];

		$dom = new DOMDocument();
		$dom->loadHTML($content, LIBXML_NOERROR | LIBXML_NOWARNING);

		$image_nodes = $dom->getElementsByTagName('img');
		$image_nodes_count = count($image_nodes);

		if (count($tag_matches) !== $image_nodes_count) {
			return $content;
		}

		for ($i = 0; $i < $image_nodes_count; $i++) {
			$attr_class = $image_nodes[$i]->getAttribute('class');
			$attr_width = $image_nodes[$i]->getAttribute('width');
			$attr_height = $image_nodes[$i]->getAttribute('height');
			$attr_alt = $image_nodes[$i]->getAttribute('alt');
			$attr_src = $image_nodes[$i]->getAttribute('src');

			$class_names = explode(' ', $attr_class);
			$wp_image_id = 0;

			// check if a 'wp-image-*' class exists and pull the ID from that
			foreach ($class_names as $class_name) {
				if (stripos($class_name, 'wp-image-') === 0) {
					$parts = explode('-', $class_name);
					$wp_image_id = isset($parts[2]) ? (int)$parts[2] : 0;
				}
			}

			// if we couldn't find an id, let's look it up by the image URL
			if (!$wp_image_id) {
				$wp_image_id = attachment_url_to_postid($attr_src);
			}

			// leave this loop if there is no id
			if (!$wp_image_id) {
				continue;
			}

			$image_sizes = self::get_image_sizes_from_acf_object($wp_image_id);
			$ib_sources_str = self::build_ib_sources_string($image_sizes);

			$attributes = [
				'src' => '"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAABCAQAAACC0sM2AAAADElEQVR42mNkGCYAAAGSAAIVQ4IOAAAAAElFTkSuQmCC"', // 100x1 transparent PNG
				'data-ib-sources' => $ib_sources_str,
				'data-ib-match-dpr' => 0,
				'class' => $attr_class,
				'alt' => $attr_alt
			];

			if ($attr_width && $settings['keep_width_height_attributes']) {
				$attributes['width'] = $attr_width;
			}

			if ($attr_height && $settings['keep_width_height_attributes']) {
				$attributes['height'] = $attr_height;
			}

			if (is_array($settings['add_attributes'])) {
				$attributes = array_merge($attributes, $settings['add_attributes']);
			}

			$attributes_str = Util::attributes_array_to_string($attributes);

			$ib_image_tag = "<img {$attributes_str} />";

			$content = str_replace($tag_matches[$i], $ib_image_tag, $content);
		}

		return $content;
	}
}
