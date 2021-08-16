import Emitter from '../util/emitter';

class ArchiveLoadMore extends Emitter {
	/**
	 * ArchiveLoadMore constructor
	 *
	 * @param {HTMLElement} containerEl The "load more" container element
	 */
	constructor(containerEl) {
		super();

		this.containerEl = containerEl;
		this.loadMoreButton = this.containerEl.querySelector('.js__load-more');

		this.lastButtonText = this.loadMoreButton ? this.loadMoreButton.textContent : '';

		if (this.loadMoreButton) {
			this.loadMoreButton.addEventListener('click', (e) => {
				e.preventDefault();

				this.emit('load');
			});
		}
	}

	/**
	 * Set the "load more" button loading status and text
	 *
	 * @param {boolean} isLoading
	 * @param {string} loadingText
	 */
	setLoading(isLoading, loadingText = 'Loading...') {
		if (!this.loadMoreButton) {
			return;
		}

		if (isLoading) {
			this.lastButtonText = this.loadMoreButton.textContent;
			this.loadMoreButton.textContent = loadingText;
			this.loadMoreButton.classList.add('disabled');
		} else {
			this.loadMoreButton.textContent = this.lastButtonText;
			this.loadMoreButton.classList.remove('disabled');
		}
	}

	/**
	 * Set the "load more" button visibility
	 *
	 * @param {boolean} isVisible
	 */
	setVisible(isVisible) {
		if (isVisible) {
			this.containerEl.classList.remove('hide');
		} else {
			this.containerEl.classList.add('hide');
		}
	}
}

export default ArchiveLoadMore;
