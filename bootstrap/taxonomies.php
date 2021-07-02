<?php
add_action('init', function () {
	// include all taxonomies from the 'taxonomies' path (exclude files that begin with '_")
	WPUtil\Util::include_all_files(get_template_directory() . '/taxonomies/*.php', function ($file) {
		return substr(basename($file), 0, 1) !== '_';
	});
}, 10);
