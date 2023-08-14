<?php
namespace WPUtil\Exceptions;

use Exception;

class GoogleMapsAPIException extends Exception
{
	public string $status_type = '';
	public array $raw_results = [];

	public function __construct(string $status_type, string $error_message = '', array $raw_results = [])
	{
		parent::__construct($error_message);

		$this->status_type = $status_type;
		$this->raw_results = $raw_results;
	}
}
