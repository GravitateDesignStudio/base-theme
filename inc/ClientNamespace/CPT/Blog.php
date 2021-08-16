<?php
namespace ClientNamespace\CPT;

use ClientNamespace\Constants;
use WPUtil\{ Query, StaticCache };
use WPUtil\Vendor\ACF;
use WP_Query;

abstract class Blog
{
	/**
	 * Return the default WP_Query for blog posts to be used in instances that
	 * do not have an existing query (ex: blocks and page templates)
	 *
	 * @param array $add_params
	 * @return WP_Query
	 */
	public static function getDefaultWPQuery(array $add_params = []): WP_Query
	{
		$params = array_merge([
			'post_type' => Constants\CPT::BLOG,
			'post_status' => 'publish',
			'posts_per_page' => self::getPostsPerPageCount()
		], $add_params);

		$params = self::modifyQueryWithFilters($params, [
			'category' => $_GET[Constants\QueryParams::BLOG_ARCHIVE_FILTER_CATEGORY] ?? '',
			'tag' => $_GET[Constants\QueryParams::BLOG_ARCHIVE_FILTER_TAG] ?? '',
			'search' => $_GET[Constants\QueryParams::BLOG_ARCHIVE_FILTER_SEARCH] ?? ''
		]);

		return new WP_Query($params);
	}

	/**
	 * Modifies a posts query object with the blog archive filter parameters.
	 * Can take either or a WP_Query object (used in "pre_get_posts") or an array
	 * (used in "rest_post_query")
	 *
	 * @param \WP_Query|array $query
	 * @param array $filters
	 * @return \WP_Query|array
	 */
	public static function modifyQueryWithFilters($query, array $filters)
	{
		$filters = array_merge([
			'category' => '',
			'tag' => '',
			'search' => ''
		], $filters);

		if ($filters['category']) {
			$query = Query::setQueryValue($query, 'category_name', $filters['category']);
		}

		if ($filters['tag']) {
			$query = Query::setQueryValue($query, 'tag', $filters['category']);
		}

		if ($filters['search']) {
			$query = Query::setQueryValue($query, 's', $filters['search']);
		}

		return $query;
	}

	/**
	 * Return the array of featured blog post ids
	 *
	 * @return array<int>
	 */
	public static function getFeaturedIds(): array
	{
		return ACF::get_field_array(
			Constants\ACF::BLOG_SETTINGS_BASE . '_featured_posts',
			'option'
		);
	}

	/**
	 * Return the custom posts per page archive settings value or default to the
	 * standard "posts_per_page" value if it isn't enabled or found
	 *
	 * @return integer
	 */
	public static function getPostsPerPageCount(): int
	{
		$value = 0;

		$override_enabled = ACF::get_field_int(
			Constants\ACF::BLOG_SETTINGS_BASE . '_override_posts_per_page',
			'option'
		);

		if ($override_enabled) {
			$value = ACF::get_field_int(
				Constants\ACF::BLOG_SETTINGS_BASE . '_custom_posts_per_page',
				'option'
			);
		}

		if (!$value) {
			$value = intval(get_option('posts_per_page'));
		}

		return $value;
	}

	/**
	 * Get the first category label attached to a customer blog post
	 *
	 * @param integer $post_id
	 * @return string
	 */
	public static function getPostCategoryText(int $post_id): string
	{
		$terms = get_the_category($post_id);

		if (!is_array($terms) || !count($terms)) {
			return '';
		}

		return $terms[0]->name ?? '';
	}

	/**
	 * Return the default image ACF object as set under "Posts / Blog Settings / Images / Default Featured Image"
	 *
	 * @return array
	 */
	public static function getDefaultCardImage(): array
	{
		$cache_key = 'blog_default_featured_image';

		$default_image = StaticCache::get($cache_key);

		if (!is_array($default_image)) {
			$default_image = ACF::get_field_array(Constants\ACF::BLOG_SETTINGS_BASE . '_default_featured_image', 'option');

			StaticCache::set($cache_key, $default_image);
		}

		return $default_image;
	}
}
