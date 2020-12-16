import { loadSwiper } from '../../util/load-dependencies';
import Modal from '../modal';
import { showVideoModal } from '../../util/modal-helpers';

class ThemeWelcome {
	constructor(el) {
		this.el = el;
		this.swiperInstances = [];

		this.initializeModals();
		this.initializeSwiper();
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

	initializeSwiper = async () => {
		try {
			const Swiper = await loadSwiper();
			const swiperContainerEls = this.el.querySelectorAll('.swiper-container');

			Array.from(swiperContainerEls).forEach((containerEl) => {
				this.swiperInstances.push(new Swiper(containerEl));
			});

			// this.$el.find('.swiper-container').each((index, el) => {
			// 	this.swiperInstances.push(new Swiper(el));
			// });
		} catch (err) {
			console.error('Swiper dynamic import failed', err);
		}
	};
}

export default ThemeWelcome;
