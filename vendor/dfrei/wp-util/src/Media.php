<?php
namespace WPUtil;

abstract class Media
{
	/**
	 * Add additional MIME upload types using a key/value array
	 * Key is the name (ex: 'svg') and the value is the MIME type (ex: 'image/svg+xml')
	 *
	 * @param array $types
	 * @return void
	 */
	public static function add_upload_mime_types(array $types): void
	{
		add_filter('upload_mimes', function($mimes) use (&$types) {
			foreach ($types as $key => $value) {
				$mimes[$key] = $value;
			}

			return $mimes;
		});
	}
}
