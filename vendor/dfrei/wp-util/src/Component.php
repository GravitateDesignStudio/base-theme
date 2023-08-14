<?php
namespace WPUtil;

use WPUtil\Interfaces\IComponent;

class Component
{
	public static function render(string $component, array $params = []): void
	{
		if (class_exists($component)) {
			$instance = new $component($params);

			if ($instance instanceof IComponent) {
				$instance->render();
			}
		} else {
			$filename = $component;

			if (!$template_file = locate_template("{$filename}.php", false, false)) {
				trigger_error("Error locating '{$filename}' for inclusion", E_USER_ERROR);
			}

			extract($params, EXTR_SKIP);

			include $template_file;
		}
	}

	public static function render_to_string(string $component, array $params = [], array $opts = []): string
	{
		$opts = array_merge([
			'strip_control_characters' => true
		], $opts);

		ob_start();

		self::render($component, $params);

		$rendered = ob_get_clean();

		if ($opts['strip_control_characters']) {
			$rendered = str_replace(["\n", "\t"], '', $rendered);
		}

		return $rendered;
	}
}
