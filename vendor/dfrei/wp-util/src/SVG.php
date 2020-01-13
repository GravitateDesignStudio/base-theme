<?php
namespace WPUtil;

abstract class SVG
{
	protected static $use_svgs = [];
	protected static $use_hook_added = false;

	/**
	 * Remove unnecessary items from SVG markup
	 *
	 * @param string $markup
	 * @return string
	 */
	protected static function clean_markup(string $markup): string
	{
		$debug = apply_filters('wputil/svg_clean_debug', false);
		$remove_ids = apply_filters('wputil/svg_clean_remove_ids', false);
		$remove_matches = apply_filters('wputil/svg_clean_remove_matches', [
			'/(<\?xml\ .*\?>)/i', // remove XML tag - causes issues with W3C validation
			'/(<!--.*-->)/i', // remove comments
			'/(\<title>[^\<]+\<\/title\>)/', // remove 'title' tag
			'/(\<desc>[^\<]+\<\/desc\>)/', // remove 'desc' tag
			'/\s\s+/', // remove 2+ sequential spaces
			'/(\n|\r)/' // remove line breaks
		]);

		$cleaned_markup = preg_replace($remove_matches, '', $markup);

		// remove 'id' attributes
		if ($remove_ids) {
			$cleaned_markup = preg_replace('/(id=\"[^\"]+\")/', '', $cleaned_markup);
		}

		if ($debug) {
			error_log($cleaned_markup);
		}

		return $cleaned_markup;
	}

	/**
	 * Get the path to SVG files. Default is <theme>/media/svg.
	 * Can be filtered using 'wputil/svg_path'
	 *
	 * @return string
	 */
	protected static function get_svg_path(): string
	{
		$svg_path = apply_filters('wputil/svg_path', get_template_directory().'/media/svg/');
		
		return trailingslashit($svg_path);
	}

	/**
	 * Get the filename for an SVG name
	 * Ex: 'play' will return '<theme>/media/svg/play.svg'
	 *
	 * @param string $svg_name
	 * @return string
	 */
	protected static function get_svg_filename(string $svg_name): string
	{
		$svg_path = self::get_svg_path();

		return $svg_path.$svg_name.'.svg';
	}

	/**
	 * Directly output SVG markup by SVG name
	 * Optional arguments:
	 *     'no_use' (boolean)
	 *         - if specified, no 'use' tag will be created for the markup
	 *     'class' (string)
	 *         - add a class to the markup container
	 *
	 * @param string $svg_name
	 * @param array $opts
	 * @return void
	 */
	public static function the_svg(string $svg_name, array $opts = []): void
	{
		echo self::get_svg($svg_name, $opts);
	}

	/**
	 * Get SVG markup by SVG name
	 * Optional arguments:
	 *     'no_use' (boolean) [default: false]
	 *         - if specified, no 'use' tag will be created for the markup
	 *     'class' (string)
	 *         - add a class to the markup container
	 *
	 * @param string $svg_name
	 * @param array $opts
	 * @return string
	 */
	public static function get_svg(string $svg_name, array $opts = []): string
	{
		$no_use = $opts['no_use'] ?? false;

		// return the file contents directly if this SVG won't be
		// setup as a "use" reference
		return $no_use ?
			self::direct_output($svg_name, $opts) :
			self::create_use_reference($svg_name, $opts);
	}

	/**
	 * Return SVG markup for use in direct output with no 'use' reference created
	 * This method is used by the_svg/get_svg and should not be called directly
	 * Optional arguments:
	 *     'class' (string)
	 *         - add a class to the markup container
	 *
	 * @param string $svg_name
	 * @param array $opts
	 * @return string
	 */
	protected static function direct_output(string $svg_name, array $opts = []): string
	{
		$filename = self::get_svg_filename($svg_name);

		if (!file_exists($filename)) {
			return '';
		}

		$dom = self::get_svg_domdocument($filename);

		if ($dom === null) {
			return '';
		}

		$svg_node = $dom->documentElement;
		
		$add_class = $opts['class'] ?? '';

		if ($add_class) {
			$class_attr = $svg_node->getAttribute('class');
			$class_attr = $class_attr ? $class_attr.' '.$add_class : $add_class;
			$svg_node->setAttribute('class', $class_attr);
		}

		return $dom->saveHTML();
	}

	/**
	 * Return a 'use' reference for SVG markup
	 * This method is used by the_svg/get_svg and should not be called directly
	 * Optional arguments:
	 *     'class' (string)
	 *         - add a class to the markup container
	 *
	 * @param string $svg_name
	 * @param array $opts
	 * @return string
	 */
	protected static function create_use_reference(string $svg_name, array $opts = []): string
	{
		// add the wp_footer hook if needed
		if (!self::$use_hook_added) {
			add_action('wp_footer', [__CLASS__, 'use_reference_hook']);
			add_action('admin_footer', [__CLASS__, 'use_reference_hook']);
			self::$use_hook_added = true;
		}

		if (!isset(self::$use_svgs[$svg_name])) {
			$filename = self::get_svg_filename($svg_name);

			if (!$filename) {
				return '';
			}

			$attrs = [];
			$dom = self::get_svg_domdocument($filename);

			if ($dom === null) {
				return '';
			}

			$svg_node = $dom->documentElement;

			if ($svg_node->hasAttributes()) {
				foreach ($svg_node->attributes as $node_attr) {
					$attrs[$node_attr->nodeName] = $node_attr->nodeValue;
				}
			}

			// set attribute defaults if needed
			$attrs['version'] = $attrs['version'] ?? '1.1';
			$attrs['xmlns'] = $attrs['xmlns'] ?? 'http://www.w3.org/2000/svg';
			$attrs['xmlns:xlink'] = $attrs['xmlns:xlink'] ?? 'http://www.w3.org/1999/xlink';
			$attrs['xml:space'] = $attrs['xml:space'] ?? 'preserve';
			$attrs['x'] = $attrs['x'] ?? '0px';
			$attrs['y'] = $attrs['y'] ?? '0px';
			
			// create markup from child nodes
			$markup = '';

			foreach ($svg_node->childNodes as $child) {
				$markup .= $child->ownerDocument->saveHTML($child);
			}

			// add to the reference array
			self::$use_svgs[$svg_name] = [
				'attributes' => $attrs,
				'markup' => $markup
			];
		}

		$attrs = self::$use_svgs[$svg_name]['attributes'];
		$add_class = $opts['class'] ?? '';

		if ($add_class) {
			$attrs['class'] = isset($attrs['class']) ? $attrs['class'].' '.$add_class : $add_class;
		}

		$attrs_str = self::create_attributes_string($attrs);
		$safe_id = self::get_safe_id_value($svg_name);

		return "<svg {$attrs_str}><use xlink:href=\"#{$safe_id}\"></use></svg>";
	}

	/**
	 * Internal callback function used to output SVG markup for all 'use' references
	 * Do not call directly
	 *
	 * @return void
	 */
	public static function use_reference_hook(): void
	{
		foreach (self::$use_svgs as $svg_name => $svg_data) {
			$attrs_str = self::create_attributes_string($svg_data['attributes']);
			$safe_id = self::get_safe_id_value($svg_name);

			?>
			<svg style="display:none" <?php echo $attrs_str; ?>>
				<symbol id="<?php echo $safe_id; ?>">
					<?php echo $svg_data['markup']; ?>
				</symbol>
			</svg>
			<?php
		}
	}

	/**
	 * Return a DOMDocument object for an SVG file
	 *
	 * @param string $filename
	 * @return \DOMDocument
	 */
	protected static function get_svg_domdocument(string $filename)
	{
		if (!file_exists($filename)) {
			return null;
		}

		$clean_markup = self::clean_markup(file_get_contents($filename));

		libxml_use_internal_errors(true);

		$dom = new \DOMDocument();
		$dom->loadHTML($clean_markup, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

		libxml_use_internal_errors(false);

		return $dom;
	}

	/**
	 * Create an HTML attributes string from a key/value array
	 *
	 * @param array $attrs
	 * @return string
	 */
	protected static function create_attributes_string(array $attrs = []): string
	{
		$attrs_parts = [];

		foreach ($attrs as $attr_name => $attr_value) {
			$attrs_parts[] = "{$attr_name}=\"{$attr_value}\"";
		}

		return implode(' ', $attrs_parts);
	}

	/**
	 * Create a safe 'id' attribute value from a string
	 *
	 * @param string $value
	 * @return string
	 */
	protected static function get_safe_id_value(string $value): string
	{
		return sanitize_title($value);
	}

	/**
	 * Get a list of available SVG files
	 * Each item in the returned array will include 'label' and 'name' keys
	 * Options:
	 *     'label_includes_dir' (boolean) [default: true]
	 *         - when false, the label value will not include the directory
	 *
	 * @param string $sub_dir
	 * @param array $opts
	 * @return array
	 */
	public static function get_svg_list(string $sub_dir = '', array $opts = []): array
	{
		$label_includes_dir = $opts['label_includes_dir'] ?? true;
		$svg_list = [];
		$svg_base_path = self::get_svg_path();
		$svg_path = $svg_base_path;

		if ($sub_dir) {
			$svg_path = trailingslashit($svg_path.$sub_dir);
		}

		try {
			$dir_iterator = new \RecursiveDirectoryIterator($svg_path, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO);
			$iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
	
			foreach ($iterator as $file) {
				if (stripos($file, '.svg') === false) {
					continue;
				}
	
				$svg_name = str_replace($svg_base_path, '', $file);
				$label = $label_includes_dir ? str_replace('.svg', '', $svg_name) : basename($svg_name, '.svg');
				$label = str_replace('/', ' / ', $label);
				$label = str_replace(['-', '_'], ' ', $label);
				$label = ucwords($label);
	
				$svg_list[] = [
					'label' => $label,
					'name' => str_replace('.svg', '', $svg_name)
				];
			}
		} catch (\Exception $e) {
			error_log(__METHOD__.' - unable to read from path: '.$svg_path);
		}

		return $svg_list;
	}
}
