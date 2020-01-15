<?php
if (isset($site)) {
	$post_id = $post_id ?? get_the_ID();
	$button_classes = ['share-icon', 'share-icon--' . $site];
	$aria_label = 'Share on social media';

	$attrs = [
		'data-social-share' => $site,
		'aria-label' => 'Share on social media'
	];

	switch ($site) {
		case 'twitter':
			$attr['aria_label'] = 'Share on Twitter';

			break;

		case 'linkedin':
			$attr['aria_label'] = 'Share on LinkedIn';

			break;

		case 'facebook':
			$attr['aria_label'] = 'Share on Facebook';
			break;

		default:
			break;
	}

	$attrs_str = Blueprint\Util::attributes_array_to_string($attrs);

	?>
	<a class="<?php echo implode(' ', $button_classes); ?>" href="<?php echo Blueprint\SocialShare::get_social_share_link($site, $post_id); ?>" <?php echo $attrs_str; ?>>
		<?php WPUtil\SVG::the_svg('social/' . $site, ['class' => 'share-icon__icon']); ?>
	</a>
	<?php
}
?>
