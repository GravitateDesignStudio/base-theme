<?php
namespace WPUtil\Models;

class ImageSize
{
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
	 * Flag indicating a cropped image size
	 *
	 * @var boolean
	 */
	public $crop = false;

	/**
	 * ImageSize constructor
	 *
	 * @param integer $width
	 * @param integer $height
	 * @param bool $crop
	 */
	public function __construct(int $width, int $height, bool $crop)
	{
		$this->width = $width;
		$this->height = $height;
		$this->crop = $crop;
	}
}
