<?php
add_editor_style('/dist/css/editor-styles.min.css');

WPUtil\TinyMCE::add_formats(array(
	array(
		'title' => 'Button (Primary)',
		'selector' => 'a',
		'classes' => 'button button-primary'
	),
	array(
		'title' => 'Button (Secondary)',
		'selector' => 'a',
		'classes' => 'button button-secondary'
	)
));

WPUtil\TinyMCE::set_options(array(
	'paste_as_text' => true
));
