<?php
namespace Blueprint;

abstract class Images
{
	public static function safe_image_output($image, $attrs = [])
	{
		if (!class_exists('GRAV_BLOCKS')) {
			return '';
		}

		echo \GRAV_BLOCKS::image($image, $attrs);
	}

	public static function get_image_sizes()
	{
		$cached_value = \Blueprint\StaticCache::get('image_sizes');

		if ($cached_value !== null) {
			return $cached_value;
		}

		global $_wp_additional_image_sizes;

		$sizes = [];

		foreach (get_intermediate_image_sizes() as $size) {
			if (in_array($size, ['thumbnail', 'medium', 'medium_large', 'large'])) {
				$sizes[$size]['width'] = get_option("{$size}_size_w");
				$sizes[$size]['height'] = get_option("{$size}_size_h");
				$sizes[$size]['crop'] = (bool)get_option("{$size}_crop");
			} else if (isset($_wp_additional_image_sizes[$size])) {
				$sizes[$size] = array(
					'width' => $_wp_additional_image_sizes[$size]['width'],
					'height' => $_wp_additional_image_sizes[$size]['height'],
					'crop' => $_wp_additional_image_sizes[$size]['crop'],
				);
			}
		}

		\Blueprint\StaticCache::set('image_sizes', $sizes);

		return \Blueprint\StaticCache::get('image_sizes');
	}

	public static function is_cropped_size($size_name)
	{
		$sizes = self::get_image_sizes();

		// check for size name key in sizes array
		if (!isset($sizes[$size_name])) {
			return false;
		}

		// check if 'crop' key exists
		if (isset($sizes[$size_name]['crop'])) {
			return $sizes[$size_name]['crop'];
		}

		return false;
	}

	public static function get_image_sizes_from_acf_object($acf_image, $opts = [])
	{
		if (is_numeric($acf_image)) {
			$acf_image = acf_get_attachment($acf_image);
		}

		if (!is_array($acf_image)) {
			return [];
		}

		$wp_image_sizes = self::get_image_sizes();
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

				$acf_image_sizes[$size] = array(
					'url' => $url,
					'width' => $acf_image['sizes'][$size.'-width'],
					'height' => $acf_image['sizes'][$size.'-height']
				);
			}
		}

		$acf_image_sizes['full'] = array(
			'url' => $acf_image['url'],
			'width' => $acf_image['width'],
			'height' => $acf_image['height']
		);

		return $acf_image_sizes;
	}

	public static function build_ib_sources_string($images)
	{
		$used_urls = [];
		$ib_sources = [];

		foreach ($images as $image) {
			if (in_array($image['url'], $used_urls)) {
				continue;
			}

			$used_urls[] = $image['url'];
			$ib_sources[] = implode(' ', array($image['url'], $image['width'], $image['height']));
		}

		return implode(', ', $ib_sources);
	}

	public static function replace_content_with_ib_images($content)
	{
		$matches = [];
		preg_match_all('(<img\ .+\/\>)', $content, $matches);

		if (!$matches || !$matches[0]) {
			return $content;
		}

		$tag_matches = $matches[0];

		$dom = new \DOMDocument();
		$dom->loadHTML($content, LIBXML_NOERROR | LIBXML_NOWARNING);

		$image_nodes = $dom->getElementsByTagName('img');

		if (count($tag_matches) !== count($image_nodes)) {
			return $content;
		}

		for ($i = 0; $i < count($image_nodes); $i++) {
			$attr_class = $image_nodes[$i]->getAttribute('class');
			$attr_width = $image_nodes[$i]->getAttribute('width');
			$attr_height = $image_nodes[$i]->getAttribute('height');
			$attr_alt = $image_nodes[$i]->getAttribute('alt');
			$attr_src = $image_nodes[$i]->getAttribute('src');

			$wp_image_id = attachment_url_to_postid($attr_src);

			if (!$wp_image_id) {
				continue;
			}

			$image_sizes = self::get_image_sizes_from_acf_object($wp_image_id);
			$ib_sources_str = self::build_ib_sources_string($image_sizes);

			$attributes['src'] = '"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAABCAQAAACC0sM2AAAADElEQVR42mNkGCYAAAGSAAIVQ4IOAAAAAElFTkSuQmCC"'; // 100x1
			$attributes['data-ib-sources'] = '"'.$ib_sources_str.'"';
			$attributes['data-ib-match-dpr'] = '"0"';
			$attributes['class'] = '"'.$attr_class.'"';
			$attributes['alt'] = '"'.$attr_alt.'"';

			if ($attr_width) {
				$attributes['width'] = '"'.$attr_width.'"';
			}

			if ($attr_height) {
				$attributes['height'] = '"'.$attr_height.'"';
			}

			$attributes_str = trim(urldecode(http_build_query($attributes, '', ' ')));

			$ib_image_tag = "<img {$attributes_str} />";

			$content = str_replace($tag_matches[$i], $ib_image_tag, $content);
		}

		return $content;
	}
}
