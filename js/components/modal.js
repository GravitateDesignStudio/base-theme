class Modal {
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

	setDefaults(defaults = {}) {
		this.defaults = {
			...this.defaults,
			...defaults
		};
	}

	getSetting(className, defaultValue = '') {
		return this.currentOpts[className] ?? this.defaults[className] ?? defaultValue;
	}

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

	createBackdropEl(modalEl = null) {
		const backdropEl = document.createElement('div');

		backdropEl.setAttribute('class', this.getSetting('modalBackdropClass'));

		if (modalEl) {
			backdropEl.appendChild(modalEl);
		}

		return backdropEl;
	}

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

	clickOutsideContentListener = (e) => {
		if (this.getSetting('closeOnOutsideClick', true) && e.target.classList.contains(this.getSetting('modalBackdropClass'))) {
			this.hide();
		}
	}

	escapeListener = (e) => {
		if (this.getSetting('closeOnEscape', true) && e.keyCode === 27) {
			this.hide();
		}
	}

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

	hide() {
		this.htmlEl.classList.remove(this.getSetting('activeHTMLelementClass'));

		if (this.currentOpts.callbacks?.onHide && typeof this.currentOpts.callbacks.onHide === 'function') {
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

	setLoading(isLoading) {
		if (isLoading) {
			this.modalEl.classList.add(this.getSetting('isLoadingClass'));
		} else {
			this.modalEl.classList.remove(this.getSetting('isLoadingClass'));
		}
	}

	setContent(content = '', removeLoader = true) {
		const contentEl = this.modalEl.querySelector(`.${this.getSetting('contentClass')}`);

		contentEl.innerHTML = content;

		if (removeLoader) {
			this.setLoading(false);
		}
	}
}

export default new Modal();
