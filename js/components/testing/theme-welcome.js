require('jquery-colorbox');

const ThemeWelcome = (function ($) {
	return class {
		constructor($el, Swiper) {
			this.$el = $el;
			this.Swiper = Swiper;
			this.swiperInstances = [];

			this.initializeSwiper();

			this.$el.find('.colorbox-trigger').on('click tap', function (e) {
				e.preventDefault();
				$.colorbox({ html: $(this).attr('data-modal-content') });
			});
		}

		initializeSwiper() {
			this.$el.find('.swiper-container').each((index, el) => {
				this.swiperInstances.push(new this.Swiper(el));
			});
		}
	};
})(jQuery);

export default ThemeWelcome;
