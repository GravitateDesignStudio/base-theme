<?php
namespace WPUtil\Interfaces;

interface IComponent
{
	/**
	 * Component constructor
	 *
	 * @param array<string, mixed> $params
	 */
	public function __construct(array $params);

	/**
	 * Render the component via direct output
	 *
	 * @return void
	 */
	public function render(): void;
}
