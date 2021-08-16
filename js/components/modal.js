/**
 * @typedef ModalDefaults
 * @property {number} closeDuration
 * @property {boolean} closeOnOutsideClick
 * @property {boolean} closeOnEscape
 * @property {string} activeHTMLelementClass
 * @property {string} ariaLabelClose
 * @property {string} closeButtonContent
 * @property {string} modalBackdropClass
 * @property {string} modalClass
 * @property {string} closeButtonClass
 * @property {string} contentContainerClass
 * @property {string} contentClass
 * @property {string} loadingContainerClass
 * @property {string} isLoadingClass
 * @property {Object} callbacks
 */

class Modal {
	/**
	 * Modal constructor
	 */
	constructor() {
		this.htmlEl = document.querySelector('html');
		this.backdropEl = null;
		this.modalEl = null;
		this.defaults = {
			closeDuration: 400,
			closeOnOutsideClick: true,
			closeOnEscape: true,
			activeHTMLelementClass: 'modal-active',
			ariaLabelClose: 'Close modal',
			closeButtonContent: '&times;',
			modalBackdropClass: 'modal-backdrop',
			modalClass: 'modal',
			closeButtonClass: 'modal__close',
			contentContainerClass: 'modal__content-container',
			contentClass: 'modal__content',
			loadingContainerClass: 'modal__loading-container',
			isLoadingClass: 'is-loading',
			callbacks: {}
		};
		this.currentOpts = { ...this.defaults };
	}

	/**
	 * Set the defaults used by all modals
	 *
	 * @param {ModalDefaults} defaults A {@link ModalDefaults} object
	 */
	setDefaults(defaults = {}) {
		this.defaults = {
			...this.defaults,
			...defaults
		};
	}

	/**
	 * Retrieve a modal setting or use a default if it does not exist
	 *
	 * @param {string} settingName The setting name
	 * @param {any} defaultValue The default value
	 * @returns {any}
	 */
	getSetting(settingName, defaultValue = '') {
		return this.currentOpts[settingName] ?? this.defaults[settingName] ?? defaultValue;
	}

	/**
	 * Initialize the modal
	 */
	initModal() {
		return new Promise((resolve) => {
			this.modalEl = this.createModalEl();
			this.backdropEl = this.createBackdropEl(this.modalEl);

			document.body.appendChild(this.backdropEl);

			const closeEl = this.modalEl.querySelector(`.${this.getSetting('closeButtonClass')}`);

			closeEl.addEventListener('click', (e) => {
				e.preventDefault();

				this.hide();
			});

			setTimeout(() => {
				resolve();
			}, 0);
		});
	}

	/**
	 * Create the modal backdrop element and append an optional child
	 *
	 * @param {HTMLElement|null} modalEl Optional modal element child to append to the backdrop element
	 * @returns {HTMLDivElement} The backdrop element
	 */
	createBackdropEl(modalEl = null) {
		const backdropEl = document.createElement('div');

		backdropEl.setAttribute('class', this.getSetting('modalBackdropClass'));

		if (modalEl) {
			backdropEl.appendChild(modalEl);
		}

		return backdropEl;
	}

	/**
	 * Create and return the modal element
	 *
	 * @returns {HTMLDivElement} The modal element
	 */
	createModalEl() {
		const modalEl = document.createElement('div');

		modalEl.setAttribute('class', this.getSetting('modalClass'));

		modalEl.innerHTML = `
			<button
				class="${this.getSetting('closeButtonClass')}"
				aria-label="${this.getSetting('ariaLabelClose')}"
				data-modal-close
			>
				${this.getSetting('closeButtonContent')}
			</button>
			<div class="${this.getSetting('contentContainerClass')}">
				<div class="${this.getSetting('loadingContainerClass')}">
					<div class="ui__spin-loader"></div>
				</div>
				<div class="${this.getSetting('contentClass')}"></div>
			</div>
		`.trim();

		return modalEl;
	}

	/**
	 * Handler for outside click events
	 *
	 * @param {Event} e
	 */
	clickOutsideContentListener = (e) => {
		if (
			this.getSetting('closeOnOutsideClick', true) &&
			e.target.classList.contains(this.getSetting('modalBackdropClass'))
		) {
			this.hide();
		}
	};

	/**
	 * Handler for an escape key press
	 *
	 * @param {Event} e
	 */
	escapeListener = (e) => {
		if (this.getSetting('closeOnEscape', true) && e.keyCode === 27) {
			this.hide();
		}
	};

	/**
	 * Display the modal
	 *
	 * @param {string} content The modal content markup
	 * @param {ModalDefaults} opts An optional {@link ModalDefaults} object
	 */
	async show(content = '', opts = {}) {
		this.currentOpts = {
			...this.defaults,
			...opts
		};

		if (this.modalEl !== null) {
			console.error('Modal.show() cannot be run since another modal is already active');
		}

		await this.initModal();

		this.htmlEl.classList.add(this.getSetting('activeHTMLelementClass'));

		if (content) {
			this.setContent(content);
		} else {
			this.setLoading(true);
		}

		this.backdropEl.addEventListener('click', this.clickOutsideContentListener);
		window.addEventListener('keydown', this.escapeListener);
	}

	/**
	 * Hide the modal
	 */
	hide() {
		this.htmlEl.classList.remove(this.getSetting('activeHTMLelementClass'));

		if (
			this.currentOpts.callbacks?.onHide &&
			typeof this.currentOpts.callbacks.onHide === 'function'
		) {
			this.currentOpts.callbacks.onHide();
		}

		this.backdropEl.removeEventListener('click', this.clickOutsideContentListener);
		window.removeEventListener('keydown', this.escapeListener);

		setTimeout(() => {
			this.modalEl.remove();
			this.modalEl = null;
			this.backdropEl.remove();
			this.backdropEl = null;
		}, this.currentOpts.closeDuration || 400);
	}

	/**
	 * Set the modal loading status
	 *
	 * @param {boolean} isLoading
	 */
	setLoading(isLoading) {
		if (isLoading) {
			this.modalEl.classList.add(this.getSetting('isLoadingClass'));
		} else {
			this.modalEl.classList.remove(this.getSetting('isLoadingClass'));
		}
	}

	/**
	 * Set the modal content markup and optionally remove the loading state
	 *
	 * @param {string} content The modal content markup
	 * @param {boolean} removeLoader Flag to remove the loading state
	 */
	setContent(content = '', removeLoader = true) {
		const contentEl = this.modalEl.querySelector(`.${this.getSetting('contentClass')}`);

		contentEl.innerHTML = content;

		if (removeLoader) {
			this.setLoading(false);
		}
	}
}

export default new Modal();
