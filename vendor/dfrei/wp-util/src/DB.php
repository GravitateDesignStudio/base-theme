<?php
namespace WPUtil;

abstract class DB
{
	/**
	 * Creates a query fragment to use with the 'LIKE' comparision
	 * method. This will generate a string that can be used to match
	 * against data stored in a serialized object. Example of the
	 * fragment returned for ID 157: s:3:"157";
	 *
	 * @param int $id
	 * @return string
	 */
	public static function id_value_in_serialized_data_selector(int $id): string
	{
		return sprintf('s:%d:"%s";', strlen((string)$id), $id);
	}
}
