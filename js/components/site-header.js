class SiteHeader {
	constructor(el) {
		this.el = el;
		this.headerHeight = 0;

		this.updateHeaderHeightVar();
		this.setupEventHandlers();
	}

	setupEventHandlers() {
		// mobile menu button handler
		const btnMobileMenu = this.el.querySelector('.site-header__mobile-menu-button');

		if (btnMobileMenu) {
			btnMobileMenu.addEventListener('click', () => {
				document.documentElement.classList.toggle('mobile-menu-active');
			});
		}

		// $('.site-header__mobile-menu-button').on('click tap', function () {
		// 	$('html').toggleClass('mobile-menu-active');
		// });

		// update header height on resize
		window.addEventListener('resize', () => this.updateHeaderHeightVar());

		// $(window).resize(() => {
		// 	this.updateHeaderHeightVar();
		// });

		// update header height on scroll
		window.addEventListener('scroll', () => this.updateHeaderHeightVar());

		// $(window).scroll(() => {
		// 	this.updateHeaderHeightVar();
		// });
	}

	updateHeaderHeightVar() {
		// this.headerHeight = this.$el.outerHeight();
		this.headerHeight = this.el.offsetHeight;
	}
}

export default SiteHeader;
