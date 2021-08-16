/**
 * Initialize the hello bar and provide a callback function that will be triggered
 * when it is shown
 *
 * @param {function} onShowCb Callback triggered when the hello bar is being shown
 */
export function initHelloBar(onShowCb) {
	window.addEventListener('hellobar-visible', (e) => {
		const isShowing = e.detail ?? false;

		if (isShowing) {
			onShowCb(document.querySelector('.hellobar'));
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
				onShowCb(document.querySelector('.hellobar'));
			}
		});
	});
}
