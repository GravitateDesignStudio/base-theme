<?php
add_filter('next_posts_link_attributes', function ($atts) {
	return 'class="archive__navigation-link archive__navigation-link--prev button button--slim"';
});

add_filter('previous_posts_link_attributes', function ($atts) {
	return 'class="archive__navigation-link archive__navigation-link--next button button--slim"';
});

?>
<div class="row archive__navigation">
	<div class="columns small-6">
		<?php next_posts_link(__('&laquo; Older Entries')); ?>
	</div>
	<div class="columns small-6 text-right">
		<?php previous_posts_link(__('Newer Entries &raquo;')); ?>
	</div>
</div>
