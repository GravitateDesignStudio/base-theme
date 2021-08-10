import { loadEmblaCarousel } from '../../util/load-dependencies';
import EmblaAutoplay from '../../util/embla/autoplay';
import EmblaPagination from '../../util/embla/pagination';
import { enableNavButtons } from '../../util/embla/util';
import Modal from '../modal';
import { showVideoModal } from '../../util/modal-helpers';
import SiteEvents, { SiteEventNames } from '../../util/site-events';

class ThemeWelcome {
	constructor(el) {
		this.el = el;
		this.emblaInstances = [];

		this.initializeModals();
		this.initializeEmbla();
	}

	initializeModals = () => {
		const btnModalDefault = this.el.querySelector('.js__example-modal--default');
		const btnModalLoading = this.el.querySelector('.js__example-modal--loading');
		const btnModalVideo = this.el.querySelector('.js__example-modal--video');

		if (btnModalDefault) {
			btnModalDefault.addEventListener('click', () => {
				Modal.show(
					'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perspiciatis tempora architecto asperiores unde omnis distinctio adipisci repellendus illum eligendi laudantium aspernatur sapiente, voluptates et sequi hic eius voluptas ullam. Tenetur sunt amet facere eligendi nihil eius reiciendis sint eos, dignissimos rerum impedit. Id culpa et est possimus nam adipisci, sint dolorum rerum doloremque deserunt. Distinctio ratione placeat, odit tempora laudantium sed sit pariatur cupiditate sint assumenda molestias? Reiciendis nam quasi, iusto dignissimos ipsa fugiat sit beatae delectus quod quaerat quia repudiandae nobis itaque possimus distinctio voluptates debitis ex assumenda. Asperiores ratione sunt expedita nesciunt, eius mollitia similique quibusdam inventore rem.'
				);
			});
		}

		if (btnModalLoading) {
			btnModalLoading.addEventListener('click', () => {
				Modal.show();

				setTimeout(() => {
					Modal.setContent('This content was set after a 2 second delay.');
				}, 2000);
			});
		}

		if (btnModalVideo) {
			btnModalVideo.addEventListener('click', (e) => {
				const videoURL = e.currentTarget.getAttribute('data-video-url');

				showVideoModal(videoURL);
			});
		}
	};

	initializeEmbla = async () => {
		try {
			const EmblaCarousel = await loadEmblaCarousel();
			const emblaContainerEls = this.el.querySelectorAll('.embla-instance');

			Array.from(emblaContainerEls).forEach((emblaRootEl) => {
				const emblaEl = emblaRootEl.querySelector('.embla');
				const btnPrevEl = emblaRootEl.querySelector('.embla__nav-button--prev');
				const btnNextEl = emblaRootEl.querySelector('.embla__nav-button--next');
				const paginationEl = emblaRootEl.querySelector('.embla__pagination');

				const transitionSpeed = emblaRootEl
					? parseInt(emblaRootEl.getAttribute('data-transition-speed') || 10, 10)
					: 10;

				const enableAutoplay = emblaRootEl
					? parseInt(emblaRootEl.getAttribute('data-enable-autoplay') || 0, 10)
					: 0;

				const autoplaySpeed = emblaRootEl
					? parseInt(emblaRootEl.getAttribute('data-autoplay-speed') || 3000, 10)
					: 3000;

				const instance = {
					embla: EmblaCarousel(emblaEl, {
						loop: true,
						speed: transitionSpeed
					}),
					pagination: null,
					autoplay: null
				};

				if (instance.embla) {
					instance.embla.on('init', () => {
						SiteEvents.publish(SiteEventNames.IMAGEBUDDY_TRIGGER_UPDATE);
					});

					if (btnPrevEl && btnNextEl) {
						enableNavButtons(instance.embla, [btnPrevEl], [btnNextEl]);
					}

					if (paginationEl) {
						instance.pagination = new EmblaPagination(instance.embla, paginationEl, {
							buttonClassName: 'embla__pagination-button'
						});
					}

					if (enableAutoplay && autoplaySpeed) {
						instance.autoplay = new EmblaAutoplay(instance.autoplay, autoplaySpeed);
					}

					this.emblaInstances.push(instance);
				}
			});
		} catch (err) {
			console.error('Embla Carousel dynamic import failed', err);
		}
	};
}

export default ThemeWelcome;
