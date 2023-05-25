<?php
// TODO: register custom endpoints for this theme using the WPUtil\REST::register_routes method
// \WPUtil\REST::register_routes('client-name/v1', array(
// '/endpoint' => 'ClientName\API\v1\Endpoint'
// ));

add_action('rest_api_init', function () {
	/**
	 * Add a 'card_markup' field to the results returned by the
	 * /wp/v2/posts endpoint
	 */
	register_rest_field('post', 'card_markup', array(
		'get_callback' => function ($post) {
			return WPUtil\Component::render_to_string(
				'components/cards/card-blog',
				[
					'post_id' => $post['id']
				]
			);
		}
	));

	/**
	 * Add a 'card_markup' field to the results returned by the
	 * /wp/v2/search endpoint
	 */
	add_filter('rest_post_dispatch', function ($result, $server, $request) {
		if ($result->get_matched_route() !== '/wp/v2/search') {
			return $result;
		}

		foreach ($result->data as &$record) {
			$record['card_markup'] = WPUtil\Component::render_to_string(
				'components/cards/card-search',
				[ 'post_id' => $record['id'] ]
			);
		}

		return $result;
	}, 10, 3);

	/**
	 * Add filters to blog (post) queries from the "load more" requests
	 */
	add_filter('rest_' . ClientNamespace\Constants\CPT::BLOG . '_query', function ($args, $request) {
		$args['posts_per_page'] = ClientNamespace\CPT\Blog::getPostsPerPageCount();

		$args = ClientNamespace\CPT\Blog::modifyQueryWithFilters($args, [
			'category' => $request->get_param(ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_CATEGORY) ?? '',
			'tag' => $request->get_param(ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_TAG) ?? '',
			'search' => $request->get_param(ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_SEARCH) ?? ''
		]);

		return $args;
	}, 10, 2);

	/**
	 * EXAMPLE:
	 * add support for adding 'service_id' and 'industry_id'
	 * parameters on project endpoints
	 */

	/*
	add_filter('rest_<CPT slug>_query', function($args, $request) {
		$service_id = $request->get_param('service_id');
		$industry_id = $request->get_param('industry_id');

		if (($service_id || $industry_id) && !isset($args['meta_query'])) {
			$args['meta_query'] = [
				'relation' => 'AND'
			];
		}

		if ($service_id) {
			$args['meta_query'][] = [
				'key' => 'services',
				'value' => \WPUtil\DB::id_value_in_serialized_data_selector($service_id),
				'compare' => 'LIKE'
			];
		}

		if ($industry_id) {
			$args['meta_query'][] = [
				'key' => 'industries',
				'value' => \WPUtil\DB::id_value_in_serialized_data_selector($industry_id),
				'compare' => 'LIKE'
			];
		}

		return $args;
	}, 10, 2);
	*/
});
