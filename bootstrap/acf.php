<?php
// remove ACF visual editor if not in a local environment
define('ACF_LITE', defined('ENVIRONMENT') ? ENVIRONMENT !== 'local' : true);

// include all ACF files from the 'acf' path (exclude files that begin with '_")
if (function_exists('acf_add_local_field_group')) {
	WPUtil\Util::include_all_files(get_template_directory() . '/acf/*.php', function ($file) {
		return substr(basename($file), 0, 1) !== '_';
	});
}

// add custom ACF fields
if (class_exists('acf_field')) {
	WPUtil\Vendor\ACF_Fields\SVGIconSelector\ACF_SVGIconSelector::init();
}
