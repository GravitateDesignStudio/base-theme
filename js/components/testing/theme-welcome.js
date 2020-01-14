import { loadSwiper } from '../../util/load-dependencies';
import Modal from '../modal';
import { showVideoModal } from '../../util/modal-helpers';

class ThemeWelcome {
	constructor($el) {
		this.$el = $el;
		this.swiperInstances = [];

		this.initializeModals();
		this.initializeSwiper();
	}

	initializeModals = () => {
		this.$el.find('.js__example-modal--default').on('click', () => {
			Modal.show('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perspiciatis tempora architecto asperiores unde omnis distinctio adipisci repellendus illum eligendi laudantium aspernatur sapiente, voluptates et sequi hic eius voluptas ullam. Tenetur sunt amet facere eligendi nihil eius reiciendis sint eos, dignissimos rerum impedit. Id culpa et est possimus nam adipisci, sint dolorum rerum doloremque deserunt. Distinctio ratione placeat, odit tempora laudantium sed sit pariatur cupiditate sint assumenda molestias? Reiciendis nam quasi, iusto dignissimos ipsa fugiat sit beatae delectus quod quaerat quia repudiandae nobis itaque possimus distinctio voluptates debitis ex assumenda. Asperiores ratione sunt expedita nesciunt, eius mollitia similique quibusdam inventore rem.');
		});

		this.$el.find('.js__example-modal--loading').on('click', () => {
			Modal.show();

			setTimeout(() => {
				Modal.setContent('This content was set after a 2 second delay.');
			}, 2000);
		});

		this.$el.find('.js__example-modal--video').on('click', (e) => {
			const videoURL = e.currentTarget.getAttribute('data-video-url');

			console.log('video url', videoURL);

			showVideoModal(videoURL);
		});
	}

	initializeSwiper = async () => {
		try {
			const Swiper = await loadSwiper();

			this.$el.find('.swiper-container').each((index, el) => {
				this.swiperInstances.push(new Swiper(el));
			});
		} catch (err) {
			console.error('Swiper dynamic import failed', err);
		}
	}
}

export default ThemeWelcome;
