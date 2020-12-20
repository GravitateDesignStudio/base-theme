<?php
add_action('init', function () {
	if (defined('ENVIRONMENT') && constant('ENVIRONMENT') === 'local') {
		add_rewrite_rule('styles/?$', 'index.php?virtual_page=styles', 'top');
	}
});

add_filter('query_vars', function ($query_vars) {
	$query_vars[] = 'virtual_page';

	return $query_vars;
});

add_action('template_redirect', function () {
	$virtual_page = trim(get_query_var('virtual_page'));

	switch ($virtual_page) {
		case 'styles':
			include get_template_directory() . '/templates/virtual/styles.php';
			die;

		default:
			break;
	}
});
