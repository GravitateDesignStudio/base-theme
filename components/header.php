<header class="site-header">
	<div class="site-header__inner">
		<div class="site-header__logo-container">
			<a href="<?php echo esc_url(site_url()); ?>" title="<?php echo esc_attr(bloginfo('name')); ?>" aria-label="Home">
				<div class="site-header__logo">
					<img src="https://via.placeholder.com/200x80" alt="">
				</div>
			</a>
		</div>
		<div class="site-header__menu-container">
			<nav class="site-header__menu site-header__menu--primary">
				<?php WPUtil\Menus::display_for_location(ClientNamespace\Constants\Menus::DESKTOP_MENU, ['depth' => 1]); ?>
			</nav>
		</div>
	</div>
</header>
<button class="site-header__mobile-menu-button ignore-default-styling hide-for-large" type="button" aria-label="Menu">
	<span class="site-header__mobile-menu-button-box">
		<span class="site-header__mobile-menu-button-inner"></span>
	</span>
</button>
<div class="site-header__mobile-container">
	<nav class="site-header__mobile-menu site-header__mobile-menu--primary">
		<?php WPUtil\Menus::display_for_location(ClientNamespace\Constants\Menus::MOBILE_MENU, ['depth' => 1]); ?>
	</nav>
</div>
