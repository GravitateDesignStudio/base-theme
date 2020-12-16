<?php
add_filter('hello_bar_fields_settings', function ($fields) {
	$fields = array_filter($fields, function ($field) {
		return $field['key'] !== 'hello_bar_icon';
	});

	return $fields;
});

add_filter('hello_bar_colors', function ($colors) {
	return [
		'hellobar--blue' => 'Blue',
		'hellobar--red' => 'Red'
	];
});
