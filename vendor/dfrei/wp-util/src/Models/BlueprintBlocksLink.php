<?php
namespace WPUtil\Models;

class BlueprintBlocksLink
{
	/**
	 * The link type
	 *
	 * @var string
	 */
	public $type = '';

	/**
	 * The link text
	 *
	 * @var string
	 */
	public $text = '';

	/**
	 * The link URL
	 *
	 * @var string
	 */
	public $link = '';

	/**
	 * The link style
	 *
	 * @var string
	 */
	public $style = '';

	/**
	 * BlueprintBlocksLink constructor
	 *
	 * @param string $type
	 * @param string $text
	 * @param string $link
	 * @param string $style
	 */
	public function __construct(string $type = '', string $text = '', string $link = '', string $style = '')
	{
		$this->type = $type;
		$this->text = $text;
		$this->link = $link;
		$this->style = $style;
	}
}
