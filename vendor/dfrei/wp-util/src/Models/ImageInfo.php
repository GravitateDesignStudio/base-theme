<?php
namespace WPUtil\Models;

class ImageInfo
{
	/**
	 * The image URL
	 *
	 * @var string
	 */
	public $url = '';

	/**
	 * The image width
	 *
	 * @var integer
	 */
	public $width = 0;

	/**
	 * The image height
	 *
	 * @var integer
	 */
	public $height = 0;

	/**
	 * ImageInfo constructor
	 *
	 * @param string $url
	 * @param integer $width
	 * @param integer $height
	 */
	public function __construct(string $url, int $width, int $height)
	{
		$this->url = $url;
		$this->width = $width;
		$this->height = $height;
	}
}
