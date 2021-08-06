<?php
use ClientNamespace\Constants;

$social_links = [];
$social_icon_fields = get_field(Constants\ACF::THEME_OPTIONS_SOCIAL_BASE . '_site_links', 'option');

if ($social_icon_fields) {
	$social_links = array_filter($social_icon_fields, function ($icon) {
		return $icon['title'] && $icon['url'] && $icon['icon'];
	});
}

?>
<footer class="site-footer bg-black">
	<div class="row">
		<div class="columns small-6 medium-8">
			<nav class="site-footer__menu site-footer__menu--primary">
				<?php WPUtil\Menus::display_for_location(Constants\Menus::FOOTER_LINKS_MENU, ['depth' => 1]); ?>
			</nav>
		</div>
		<div class="columns small-6 medium-4">
			<div class="site-footer__social-links">
				<?php
				foreach ($social_links as $social_link) {
					?>
					<a href="<?php echo esc_attr($social_link['url']); ?>"
						class="site-footer__social-link"
						rel="external noopener nofollow"
						target="_blank"
						title="<?php echo esc_attr($social_link['title']); ?>">
						<span class="site-footer__social-icon">
							<?php Blueprint\SVG::the_svg($social_link['icon']); ?>
						</span>
					</a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="columns small-12 site-footer__legal">
			  <p class="site-footer__copyright">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php the_field(Constants\ACF::THEME_OPTIONS_FOOTER_BASE . '_copyright_text', 'option'); ?></p>
			  <nav class="site-footer__menu site-footer__menu--secondary">
				  <?php WPUtil\Menus::display_for_location(Constants\Menus::FOOTER_LEGAL_MENU, ['depth' => 1]); ?>
			  </nav>
		 </div>
	</div>
</footer>
