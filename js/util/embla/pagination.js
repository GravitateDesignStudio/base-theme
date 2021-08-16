/**
 * @typedef EmblaPaginationOpts
 * @property {string} buttonClassName The pagination button classname
 * @property {string} activeClassName An additional classname added to the active pagination button
 */

class EmblaPagination {
	/**
	 * Initialize pagination support for the provided Embla instance
	 *
	 * @param {Object} embla The Embla instance
	 * @param {HTMLElement} paginationEl The HTML element where pagination buttons will be added
	 * @param {EmblaPaginationOpts} opts An {@link EmblaPaginationOpts} options object
	 * @returns
	 */
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

	/**
	 * Create the pagination buttons markup
	 */
	createButtonsMarkup() {
		return this.emblaInstance
			.scrollSnapList()
			.reduce(
				(acc) =>
					acc + `<button class="${this.opts.buttonClassName}" type="button"></button>`,
				''
			);
	}

	/**
	 * Set the currently active pagination button
	 */
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
