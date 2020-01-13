const HelloBar = (function ($) {
	function adjustElements() {
		const hbHeight = $('.hellobar').outerHeight();

		$('body, .site-header').css('margin-top', hbHeight);
	}

	function init() {
		$(window).on('hellobar-visible', function (event, isShowing) {
			if (isShowing) {
				adjustElements();
			} else {
				$('body, .site-header').css('margin-top', 0);
			}
		});

		window.addEventListener('resize', () => {
			requestAnimationFrame(() => {
				if ($('body').hasClass('hellobar--visible')) {
					adjustElements();
				}
			});
		});
	}

	return {
		init
	};
})(jQuery);

export default HelloBar;
