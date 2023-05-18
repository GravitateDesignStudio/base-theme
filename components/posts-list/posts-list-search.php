<?php
$wp_query_obj = $args['wp_query_obj'];
$block_display = $block_display ?? false;
$current_page = isset($current_page) && is_int($current_page) ? $current_page : 1;

$no_results_message = isset($no_results_message) && is_string($no_results_message)
	? $no_results_message
	: WPUtil\Vendor\ACF::get_field_string(
		ClientNamespace\Constants\ACF::THEME_OPTIONS_SEARCH_BASE . '_no_results_message',
		'option',
		[
			'default' => ClientNamespace\Constants\DefaultValues::SEARCH_NO_RESULTS_MESSAGE
		]
	);

WPUtil\Component::render('components/posts-list/posts-list', [
	'wp_query_obj' => $wp_query_obj,
	'card_component' => 'components/cards/card-search',
	'block_display' => $block_display,
	'add_container_classes' => ['posts-list--search'],
	'current_page' => $current_page,
	'no_results_message' => $no_results_message,
	'card_col_classes' => ['columns', 'small-12'],
	'filter_render_functions' => [
		// search
		function () {
			$cur_search = $_GET['s'] ?? '';

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
		}
	]
]);
