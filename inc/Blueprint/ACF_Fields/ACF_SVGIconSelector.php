<?php

namespace Blueprint\ACF_Fields;

class ACF_SVGIconSelector extends \acf_field
{
	protected static $instance = null;

	public function __construct($settings = [])
	{
		$this->name = 'svg-icon-selector';
		$this->label = 'SVG Icon Selector';
		$this->category = 'choice';

		parent::__construct();
	}

	public static function init()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
	}

	public static function get_instance()
	{
		if (!self::$instance) {
			self::init();
		}

		return self::$instance;
	}

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

	public function render_field($field)
	{
		$svg_list = \WPUtil\SVG::get_svg_list($field['svg_sub_dir']);
		$preview_width = $field['selected_width'] ?? 48;
		$preview_height = $field['selected_height'] ?? 48;

		?>
		<input class="svg-icon-selector__value" type="hidden" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr($field['value']); ?>" />

		<div class="svg-icon-selector__preview" style="width: <?php echo $preview_width; ?>px; height: <?php echo $preview_height; ?>px;">
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

	public function input_admin_enqueue_scripts()
	{
		$js_url = get_template_directory_uri() . '/inc/Blueprint/ACF_Fields/ACF_SVGIconSelector.js';
		$css_url = get_template_directory_uri() . '/inc/Blueprint/ACF_Fields/ACF_SVGIconSelector.css';

		$js_path = get_template_directory() . '/inc/Blueprint/ACF_Fields/ACF_SVGIconSelector.js';
		$css_path = get_template_directory() . '/inc/Blueprint/ACF_Fields/ACF_SVGIconSelector.js';

		wp_enqueue_script('svg-icon-selector', $js_url, [], filemtime($js_path), true);
		wp_enqueue_style('svg-icon-selector', $css_url, [], filemtime($css_path));
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
