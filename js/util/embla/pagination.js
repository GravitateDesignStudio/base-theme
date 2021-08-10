class EmblaPagination {
	constructor(embla, paginationEl, opts = {}) {
		if (!embla || !paginationEl) {
			return;
		}

		this.opts = {
			buttonClassName: 'embla__pagination-button',
			activeClassName: 'active',
			...opts
		};

		this.emblaInstance = embla;
		this.paginationEl = paginationEl;

		this.paginationEl.insertAdjacentHTML('afterbegin', this.createButtonsMarkup());

		this.paginationDotEls = Array.from(
			this.paginationEl.querySelectorAll(`.${this.opts.buttonClassName}`)
		);

		this.paginationDotEls.forEach((el, i) =>
			el.addEventListener('click', () => this.emblaInstance.scrollTo(i), false)
		);

		this.emblaInstance.on('init', () => {
			this.setActivePaginationButtons();
		});

		this.emblaInstance.on('select', this.setActivePaginationButtons);
	}

	createButtonsMarkup() {
		return this.emblaInstance
			.scrollSnapList()
			.reduce(
				(acc) =>
					acc + `<button class="${this.opts.buttonClassName}" type="button"></button>`,
				''
			);
	}

	setActivePaginationButtons = () => {
		if (!this.emblaInstance || !this.paginationDotEls.length) {
			return;
		}

		const prevIndex = this.emblaInstance.previousScrollSnap();
		const curIndex = this.emblaInstance.selectedScrollSnap();

		this.paginationDotEls[prevIndex].classList.remove(this.opts.activeClassName);
		this.paginationDotEls[curIndex].classList.add(this.opts.activeClassName);
	};
}

export default EmblaPagination;
