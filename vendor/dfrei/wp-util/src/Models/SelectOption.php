<?php
namespace WPUtil\Models;

class SelectOption
{
	/**
	 * The option "value" attribute
	 *
	 * @var string
	 */
	public $value = '';

	/**
	 * The option label
	 *
	 * @var string
	 */
	public $label = '';

	/**
	 * Return the rendered the option markup
	 *
	 * @param string $selected_value If this value matches the current option value the "selected" attribute will be applied
	 * @return string The rendered option markup
	 */
	public function render(string $selected_value = ''): string
	{
		$is_selected = $selected_value === $this->value;

		$safe_value = esc_attr($this->value);
		$safe_label = esc_html($this->label);

		if ($is_selected) {
			$markup = sprintf('<option value="%s" selected>%s</option>', $safe_value, $safe_label);
		} else {
			$markup = sprintf('<option value="%s">%s</option>', $safe_value, $safe_label);
		}

		return $markup;
	}
}
