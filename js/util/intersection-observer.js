/**
 * Callback signature for createSingleUseObserver
 *
 * @callback createSingleUseObserverCallback
 * @param {IntersectionObserverEntry} targetEntry The IntersectionObserverEntry object generated
 * @param {HTMLElement} el A reference to the watched element
 */

/**
 * Create a single use IntersectionObserver that watches for the specified
 * element to scroll into view
 *
 * @param {HTMLElement} el The HTML element to be observed
 * @param {createSingleUseObserverCallback} cbFunc The callback function triggered when the watched element scrolls into view
 * @param {Object} opts An objects object that can contain "vpWidthThresholds" and "triggerIfBelow" keys
 * @param {Object} opts.vpWidthThresholds A key/value object with keys specifying the minimum viewport width and the value indicating the threshold value
 * @param {boolean} opts.triggerIfBelow If set to true, the watched element will be triggered if the initial page load puts the user below the specified element
 */
export function createSingleUseObserver(el, cbFunc, opts = {}) {
	opts = {
		vpWidthThresholds: { 0: 0.15, 641: 0.2, 1025: 0.35 },
		triggerIfBelow: false,
		...opts
	};

	let vpThreshold = 0.35;

	if (typeof opts.vpWidthThresholds === 'object') {
		const threshKeys = Object.keys(opts.vpWidthThresholds)
			.map((width) => parseInt(width, 10))
			.sort((a, b) => a - b);

		if (threshKeys.length) {
			vpThreshold = opts.vpWidthThresholds[threshKeys[0]];

			threshKeys.forEach((vpWidth) => {
				if (window.innerWidth >= vpWidth) {
					vpThreshold = opts.vpWidthThresholds[vpWidth];
				}
			});
		}
	}

	let firstObserve = true;

	const observer = new IntersectionObserver(
		(observerEntries) => {
			const targetEntry = observerEntries[0] || null;

			if (!targetEntry) {
				observer.disconnect();
				return;
			}

			if ((firstObserve && opts.triggerIfBelow) || targetEntry.isIntersecting) {
				cbFunc(targetEntry, el);
				observer.disconnect();
			}

			firstObserve = false;
		},
		{
			threshold: vpThreshold
		}
	);

	observer.observe(el);

	return observer;
}
