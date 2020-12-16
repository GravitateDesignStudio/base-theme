import Emitter from '../util/emitter';

class ArchiveLoadMore extends Emitter {
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

	setVisible(isVisible) {
		if (isVisible) {
			this.containerEl.classList.remove('hide');
		} else {
			this.containerEl.classList.add('hide');
		}
	}
}

export default ArchiveLoadMore;
