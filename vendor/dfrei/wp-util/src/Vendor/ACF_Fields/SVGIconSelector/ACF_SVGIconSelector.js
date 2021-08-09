(function () {
	function getFieldRef(el) {
		const parentEl = el.closest('.acf-input');

		if (!parentEl) {
			return null;
		}

		const hiddenFieldEl = parentEl.querySelector('.svg-icon-selector__value');

		if (!hiddenFieldEl) {
			return null;
		}

		const fieldRef = hiddenFieldEl.getAttribute('name');

		return fieldRef || null;
	}

	function getFieldRefChildEl(fieldRef, selector) {
		const inputEl = document.querySelector(`.svg-icon-selector__value[name="${fieldRef}"]`);

		if (!inputEl) {
			return null;
		}

		const parentEl = inputEl.closest('.acf-input');

		if (!parentEl) {
			return null;
		}

		return parentEl.querySelector(selector);
	}

	function getCurrentValue(fieldRef) {
		const inputValueEl = document.querySelector(`input[name="${fieldRef}"]`);

		return inputValueEl ? inputValueEl.value : '';
	}

	function closeIconModal() {
		const modal = document.querySelector('.svg-icon-selector__modal');

		if (modal) {
			modal.remove();
		}
	}

	function showIconModal(fieldRef) {
		// remove existing modal if it exists
		closeIconModal();

		// get icons from hidden selector
		const iconsContainerEl = getFieldRefChildEl(fieldRef, '.svg-icon-selector__selectable-icons');

		if (!iconsContainerEl) {
			console.error('cannot find selectable icons element for field ref', fieldRef);
		}

		const iconsHTML = iconsContainerEl.innerHTML;

		// create the modal element
		const modalEl = document.createElement('div');
		modalEl.classList.add('svg-icon-selector__modal');
		modalEl.setAttribute('data-field', fieldRef);
		modalEl.innerHTML = `
			<div class="svg-icon-selector__modal-inner">
				<button class="svg-icon-selector__modal-close" type="button" role="button">&times;</button>
				<div class="svg-icon-selector__selectable-icon-grid">${iconsHTML}</div>
			</div>
		`;

		const curVal = getCurrentValue(fieldRef);
		const curValEl = modalEl.querySelector(`.svg-icon-selector__selectable-icon[data-name="${curVal}"]`);

		if (curValEl) {
			curValEl.classList.add('active');
		}

		document.body.append(modalEl);
	}

	document.addEventListener('DOMContentLoaded', () => {
		document.addEventListener('click', (e) => {
			// is this a show modal trigger?
			if (e.target.classList.contains('svg-icon-selector__trigger')) {
				e.preventDefault();

				const fieldRef = getFieldRef(e.target);

				if (!fieldRef) {
					console.error('SVG icon selector: unable to find field ref for element', el.target);
					return;
				}

				showIconModal(fieldRef, []);

				return;
			}

			// is this a click outside the modal or the close icon?
			if (
				e.target.classList.contains('svg-icon-selector__modal') ||
				e.target.classList.contains('svg-icon-selector__modal-close')
			) {
				closeIconModal();
				return;
			}

			const iconEl = e.target.closest('.svg-icon-selector__selectable-icon');

			if (!iconEl) {
				return;
			}

			const modalEl = document.querySelector('.svg-icon-selector__modal');

			if (!modalEl) {
				return;
			}

			const selectedValue = iconEl.getAttribute('data-name');
			const fieldRef = modalEl.getAttribute('data-field');

			const previewEl = getFieldRefChildEl(fieldRef, '.svg-icon-selector__preview');
			const inputValueEl = document.querySelector(`.svg-icon-selector__value[name="${fieldRef}"]`);

			inputValueEl.value = selectedValue;

			if (previewEl) {
				previewEl.innerHTML = iconEl.innerHTML;
			}

			closeIconModal();
		});
	});
})();
