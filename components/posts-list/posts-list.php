<?php
// $wp_query_obj = isset($wp_query_obj) && is_a($wp_query_obj, 'WP_Query') ? $wp_query_obj : null;
$card_component = isset($card_component) && is_string($card_component) ? $card_component : 'components/cards/card-resource';
$block_display = $block_display ?? false;
$featured_ids = isset($featured_ids) && is_array($featured_ids) ? $featured_ids : [];
$add_container_classes = isset($add_container_classes) && is_array($add_container_classes) ? $add_container_classes : [];
$featured_col_classes = isset($featured_col_classes) && is_array($featured_col_classes) ? $featured_col_classes : ['columns', 'small-12', 'medium-6'];
$card_col_classes = isset($card_col_classes) && is_array($card_col_classes) ? $card_col_classes : ['columns', 'small-12', 'medium-6', 'large-3'];
$current_page = isset($current_page) && is_int($current_page) ? $current_page : 1;
$no_results_message = isset($no_results_message) && is_string($no_results_message) ? $no_results_message : '';

$featured_render_params_callback = isset($featured_render_params_callback) ? $featured_render_params_callback : null;
$card_render_params_callback = isset($card_render_params_callback) ? $card_render_params_callback : null;
$pre_card_column_callback = isset($pre_card_column_callback) ? $pre_card_column_callback : null;

$filter_render_functions = isset($filter_render_functions) && is_array($filter_render_functions) ? $filter_render_functions : [];

if ($wp_query_object && $card_component)
{
	$container_classes = array_merge(['posts-list'], $add_container_classes);

	if ($block_display) {
		$container_classes[] = 'posts-list--block';
	}

	?>
	<div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">
		<?php
		if ($featured_ids)
		{
			?>
			<div class="row posts-list__row-featured">
				<?php
				foreach ($featured_ids as $featured_id)
				{
					?>
					<div class="<?php echo esc_attr(implode(' ', $featured_col_classes)); ?>">
						<?php
						$params = [
							'post_id' => $featured_id,
							'is_large' => true
						];

						if (is_callable($featured_render_params_callback)) {
							$params = $featured_render_params_callback($params, $featured_id);
						}

						WPUtil\Component::render($card_component, $params);
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}

		if ($filter_render_functions)
		{
			?>
			<div class="row posts-list__row-filters small-up-1 medium-up-<?php echo count($filter_render_functions); ?>">
				<?php
				foreach ($filter_render_functions as $filter_render_func)
				{
					?>
					<div class="columns">
						<?php
						if (is_callable($filter_render_func))
						{
							$filter_render_func();
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}

		$total_pages = intval($wp_query_obj->max_num_pages ?? 1);
		$posts_per_page = intval($wp_query_obj->query_vars['posts_per_page'] ?? get_option('posts_per_page'));

		$no_results_classes = [
			'row',
			'align-center',
			'posts-list__no-results-container'
		];

		if ($wp_query_object->posts) {
			$no_results_classes[] = 'hide';
		}

		?>
		<div class="row posts-list__cards-container"
			data-load-more-target
			data-current-page="<?php echo esc_attr($current_page); ?>"
			data-total-pages="<?php echo esc_attr($total_pages); ?>"
			data-posts-per-page="<?php echo esc_attr($posts_per_page); ?>"
			>
			<?php
			foreach ($wp_query_object->posts as $cur_index => $cur_post)
			{
				if (is_callable($pre_card_column_callback)) {
					$pre_card_column_callback($cur_index, $cur_post->ID);
				}

				?>
				<div class="<?php echo esc_attr(implode(' ', $card_col_classes)); ?>">
					<?php
					$params = [
						'post_id' => $cur_post->ID
					];

					if (is_callable($card_render_params_callback)) {
						$params = $card_render_params_callback($params, $cur_post->ID);
					}

					WPUtil\Component::render($card_component, $params);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<div class="<?php echo esc_attr(implode(' ', $no_results_classes)); ?>" data-no-results-container>
			<div class="columns small-12 large-10 text-center">
				<h4><?php echo esc_html($no_results_message); ?></h4>
			</div>
		</div>
		<div class="posts-list__loader-container hide">
			<span class="posts-list__loader-spinner"></span>
		</div>
		<?php

		get_template_part('components/archive/load-more', '', [
			'wp_query_object' => $wp_query_object,
		]);
		?>
	</div>
	<?php
}
