<?php
global $paged, $wp_query;

$max_page = $wp_query->max_num_pages;

if (!$paged) {
	$paged = 1;
}

$nextpage = intval($paged) + 1;

if (!is_single() && ($nextpage <= $max_page)) {
	$button_text = $button_text ?? 'Load More';

	?>
	<div class="archive__load-more-container faux-row">
		<a href="#" class="button js__load-more"><?php echo esc_html($button_text); ?></a>
	</div>
	<?php
}
