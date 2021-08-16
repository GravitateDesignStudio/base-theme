<?php
$wp_query_obj = isset($wp_query_obj) && is_a($wp_query_obj, 'WP_Query') ? $wp_query_obj : null;
$block_display = $block_display ?? false;
$current_page = isset($current_page) && is_int($current_page) ? $current_page : 1;

$no_results_message = isset($no_results_message) && is_string($no_results_message)
	? $no_results_message
	: WPUtil\Vendor\ACF::get_field_string(
		ClientNamespace\Constants\ACF::BLOG_SETTINGS_BASE . '_no_results_message',
		'option',
		[
			'default' => ClientNamespace\Constants\DefaultValues::ARCHIVE_NO_RESULTS_MESSAGE
		]
	);

WPUtil\Component::render('components/posts-list/posts-list', [
	'wp_query_obj' => $wp_query_obj,
	'card_component' => 'components/cards/card-blog',
	'block_display' => $block_display,
	'featured_ids' => ClientNamespace\CPT\Blog::getFeaturedIds(),
	'add_container_classes' => ['posts-list--blog'],
	'current_page' => $current_page,
	'no_results_message' => $no_results_message,
	'featured_render_params_callback' => function ($params, $post_id) {
		$params['category'] = ClientNamespace\CPT\Blog::getPostCategoryText($post_id);
		$params['default_image'] = ClientNamespace\CPT\Blog::getDefaultCardImage();

		return $params;
	},
	'card_render_params_callback' => function ($params, $post_id) {
		$params['category'] = ClientNamespace\CPT\Blog::getPostCategoryText($post_id);
		$params['default_image'] = ClientNamespace\CPT\Blog::getDefaultCardImage();

		return $params;
	},
	'filter_render_functions' => [
		// search
		function () {
			$cur_search = $_GET[ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_SEARCH] ?? '';

			?>
			<label for="filter_search"><?php echo __('Search', ClientNamespace\Constants\TextDomains::DEFAULT); ?></label>
			<?php
			WPUtil\Component::render('components/ui/input-with-icon', [
				'is_form' => true,
				'add_container_attrs' => [
					'id' => 'form_filter_search'
				],
				'add_container_classes' => [
					'posts-list__filter-search'
				],
				'add_input_attrs' => [
					'id' => 'filter_search',
					'name' => 'filter_search',
					'placeholder' => __('Enter Keyword', ClientNamespace\Constants\TextDomains::DEFAULT),
					'value' => $cur_search
				],
				'add_icon_container_attrs' => [
					'aria-label' => __('Search', ClientNamespace\Constants\TextDomains::DEFAULT)
				],
			]);
		},
		// category
		function () {
			$cur_category = $_GET[ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_CATEGORY] ?? '';

			$options = WPUtil\Taxonomy::get_taxonomy_filter_options(
				ClientNamespace\Constants\Taxonomies::BLOG_CATEGORY
			);

			?>
			<label for="filter_category"><?php echo __('Category', ClientNamespace\Constants\TextDomains::DEFAULT); ?></label>
			<select class="posts-list__filter-select" name="filter_category" id="filter_category">
				<option value=""><?php echo __('All Categories', ClientNamespace\Constants\TextDomains::DEFAULT); ?></option>
				<?php
				foreach ($options as $option)
				{
					echo $option->render($cur_category);
				}
				?>
			</select>
			<?php
		},
		// tag
		function () {
			$cur_tag = $_GET[ClientNamespace\Constants\QueryParams::BLOG_ARCHIVE_FILTER_TAG] ?? '';

			$options = WPUtil\Taxonomy::get_taxonomy_filter_options(
				ClientNamespace\Constants\Taxonomies::BLOG_TAG
			);

			?>
			<label for="filter_tag"><?php echo __('Tag', ClientNamespace\Constants\TextDomains::DEFAULT); ?></label>
			<select class="posts-list__filter-select" name="filter_tag" id="filter_tag">
				<option value=""><?php echo __('All Tags', ClientNamespace\Constants\TextDomains::DEFAULT); ?></option>
				<?php
				foreach ($options as $option)
				{
					echo $option->render($cur_tag);
				}
				?>
			</select>
			<?php
		}
	]
]);
