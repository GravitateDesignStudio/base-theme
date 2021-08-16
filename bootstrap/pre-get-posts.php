<?php
add_action('pre_get_posts', function ($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	/**
	 * Search archive
	 */
	if ($query->is_search()) {
		$query->set('posts_per_page', ClientNamespace\Search::getPostsPerPageCount());
	}

	/**
	 * Blog (post) archive filtering
	 */
	if ($query->is_home()) {
		$query->set('posts_per_page', ClientNamespace\CPT\Blog::getPostsPerPageCount());

		$query = ClientNamespace\CPT\Blog::modifyQueryWithFilters($query, [
			'category' => $_GET[ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_CATEGORY] ?? '',
			'tag' => $_GET[ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_TAG] ?? '',
			'search' => $_GET[ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_SEARCH] ?? ''
		]);
	}
});
