import Emitter from '../util/emitter';

class ArchiveLoadMore extends Emitter {
	constructor($containerEl) {
		super();

		this.$containerEl = $containerEl;
		this.$loadMoreButton = this.$containerEl.find('.js__load-more');

		this.lastButtonText = this.$loadMoreButton.text();

		this.$loadMoreButton.on('click', (e) => {
			e.preventDefault();

			this.emit('load');
		});
	}

	setLoading(isLoading, loadingText = 'Loading...') {
		if (isLoading) {
			this.lastButtonText = this.$loadMoreButton.text();
			this.$loadMoreButton.text(loadingText);
			this.$loadMoreButton.addClass('disabled');
		} else {
			this.$loadMoreButton.text(this.lastButtonText);
			this.$loadMoreButton.removeClass('disabled');
		}
	}

	setVisible(isVisible) {
		if (isVisible) {
			this.$containerEl.removeClass('hide');
		} else {
			this.$containerEl.addClass('hide');
		}
	}
}

export default ArchiveLoadMore;
