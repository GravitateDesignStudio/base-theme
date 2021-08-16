<?php
$is_form = $is_form ?? false;
$icon = isset($icon) && is_string($icon) ? trim($icon) : 'general/chevron-right';
$add_container_attrs = isset($add_container_attrs) && is_array($add_container_attrs) ? $add_container_attrs : [];
$add_container_classes = isset($add_container_classes) && is_array($add_container_classes) ? $add_container_classes : [];
$add_input_attrs = isset($add_input_attrs) && is_array($add_input_attrs) ? $add_input_attrs : [];
$add_input_classes = isset($add_input_classes) && is_array($add_input_classes) ? $add_input_classes : [];
$add_icon_container_attrs = isset($add_icon_container_attrs) && is_array($add_icon_container_attrs) ? $add_icon_container_attrs : [];
$add_icon_container_classes = isset($add_icon_container_classes) && is_array($add_icon_container_classes) ? $add_icon_container_classes : [];
$add_icon_classes = isset($add_icon_classes) && is_array($add_icon_classes) ? $add_icon_classes : [];

$container_classes = array_merge([
	'input-with-icon'
], $add_container_classes);

$input_classes = array_merge([
	'input-with-icon__input'
], $add_input_classes);

$icon_container_classes = array_merge([
	'input-with-icon__submit'
], $add_icon_container_classes);

$default_container_attrs = [
	'class' => implode(' ', $container_classes)
];

if ($is_form) {
	$default_container_attrs['action'] = esc_url(home_url());
	$default_container_attrs['method'] = 'get';
}

$container_attrs = array_merge($default_container_attrs, $add_container_attrs);

$input_attrs = array_merge([
	'type' => 'text',
	'name' => 's',
	'class' => implode(' ', $input_classes)
], $add_input_attrs);

$icon_container_attrs = array_merge([
	'type' => 'submit',
	'class' => implode(' ', $icon_container_classes)
], $add_icon_container_attrs);

$icon_classes = array_merge([
	'input-with-icon__icon'
], $add_icon_classes);

$tag_container = $is_form ? 'form' : 'div';
$tag_icon = $is_form ? 'button' : 'div';

?>
<<?php echo $tag_container; ?> <?php echo WPUtil\Util::attributes_array_to_string($container_attrs); ?>>
	<input <?php echo WPUtil\Util::attributes_array_to_string($input_attrs); ?> />
	<<?php echo $tag_icon; ?> <?php echo WPUtil\Util::attributes_array_to_string($icon_container_attrs); ?>>
		<?php WPUtil\SVG::the_svg($icon, ['class' => implode(' ', $icon_classes)]); ?>
	</<?php echo $tag_icon; ?>>
	<?php
	if ($is_form)
	{
		?>
		<span class="ui__spin-loader"></span>
		<?php
	}
	?>
</<?php echo $tag_container; ?>>
