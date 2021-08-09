<?php

namespace WPUtil\Vendor\ACF_Fields\SVGIconSelector;

class ACF_SVGIconSelector extends \acf_field
{
	protected static $instance = null;

	/**
	 * ACF_SVGIconSelector constructor
	 *
	 * @param array $settings
	 */
	public function __construct($settings = [])
	{
		$this->name = 'svg-icon-selector';
		$this->label = 'SVG Icon Selector';
		$this->category = 'choice';

		parent::__construct();
	}

	/**
	 * Init method to return a singleton instance if needed
	 *
	 * @return void
	 */
	public static function init()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
	}

	/**
	 * Get the single instance
	 *
	 * @return self
	 */
	public static function get_instance()
	{
		if (!self::$instance) {
			self::init();
		}

		return self::$instance;
	}

	/**
	 * Render the field settings
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field_settings($field)
	{
		acf_render_field_setting($field, [
			'label'         => 'Allow None',
			'instructions'  => 'Enable the ability to select "none"',
			'type'          => 'true_false',
			'name'          => 'allow_none'
		]);

		acf_render_field_setting($field, [
			'label'         => 'Sub-directory',
			'instructions'  => 'Limit icon selections to the specified sub-directory',
			'type'          => 'text',
			'name'          => 'svg_sub_dir'
		]);
	}

	/**
	 * Render the field
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field($field)
	{
		$svg_list = \WPUtil\SVG::get_svg_list($field['svg_sub_dir']);
		$preview_width = $field['selected_width'] ?? 48;
		$preview_height = $field['selected_height'] ?? 48;

		?>
		<input class="svg-icon-selector__value" type="hidden" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr($field['value']); ?>" />

		<div class="svg-icon-selector__preview" style="width: <?php echo esc_attr($preview_width); ?>px; height: <?php echo esc_attr($preview_height); ?>px;">
			<?php
			if ($field['value']) {
				\WPUtil\SVG::the_svg($field['value']);
			} else {
				?>
				None
				<?php
			}
			?>
		</div>

		<button class="svg-icon-selector__trigger">Select Icon</button>
		<div class="svg-icon-selector__selectable-icons">
			<?php
			if ($field['allow_none']) {
				?>
				<div class="svg-icon-selector__selectable-icon" data-name="" data-label="None">
					None
				</div>
				<?php
			}

			foreach ($svg_list as $svg_item) {
				?>
				<div class="svg-icon-selector__selectable-icon" data-name="<?php echo esc_attr($svg_item['name']); ?>" data-label="<?php echo esc_attr($svg_item['label']); ?>">
					<?php \WPUtil\SVG::the_svg($svg_item['name']); ?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Enqueue the JS and CSS files needed for the SVG icon selector field.
	 * These values can be modified with the "WPUtil/ACF_Fields/SVGIconSelector/<js_url|css_url>" filters.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts()
	{
		$sub_path = '/vendor/dfrei/wp-util/src/Vendor/ACF_Fields/SVGIconSelector';
		$filter_base = 'WPUtil/ACF_Fields/SVGIconSelector';

		$js_url = apply_filters($filter_base . '/js_url', get_template_directory_uri() . $sub_path . '/ACF_SVGIconSelector.js');
		$css_url = apply_filters($filter_base . '/css_url', get_template_directory_uri() . $sub_path . '/ACF_SVGIconSelector.css');

		$js_path = '';
		$css_path = '';

		if (stripos($js_url, get_template_directory_uri()) !== false) {
			$js_path = str_ireplace(get_template_directory_uri(), get_template_directory(), $js_url);
		}

		if (stripos($css_url, get_template_directory_uri()) !== false) {
			$css_path = str_ireplace(get_template_directory_uri(), get_template_directory(), $css_url);
		}

		wp_enqueue_script('svg-icon-selector', $js_url, [], $js_path ? filemtime($js_path) : '', true);
		wp_enqueue_style('svg-icon-selector', $css_url, [], $css_path ? filemtime($css_path) : '');
	}

	public function update_value($value, $post_id, $field)
	{
		return $value;
	}

	public function load_value($value, $post_id, $field)
	{
		return $value;
	}

	public function validate_value($valid, $value, $field, $input)
	{
		return $field['allow_none'] ? true : (bool)trim($value);
	}
}
