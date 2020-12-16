const HelloBar = function () {
	function adjustElements() {
		const hbEl = document.querySelector('.hellobar');

		if (hbEl) {
			document.body.style.marginTop = `${hbEl.offsetHeight}px`;

			const siteHeaderEl = document.querySelector('.site-header');

			if (siteHeaderEl) {
				siteHeaderEl.style.marginTop = `${hbEl.offsetHeight}px`;
			}
		}
	}

	function init() {
		window.addEventListener('hellobar-visible', (e) => {
			const isShowing = e.detail ?? false;

			if (isShowing) {
				adjustElements();
			} else {
				document.body.style.marginTop = '0px';

				const siteHeaderEl = document.querySelector('.site-header');

				if (siteHeaderEl) {
					siteHeaderEl.style.marginTop = '0px';
				}
			}
		});

		window.addEventListener('resize', () => {
			requestAnimationFrame(() => {
				if (document.body.classList.contains('hellobar--visible')) {
					adjustElements();
				}
			});
		});
	}

	return {
		init
	};
};

export default HelloBar;
