import { loadIntersectionObserver } from './load-dependencies';

function createElementObserver(el) {
	// large threshold
	let vpThreshold = 0.35;

	if (window.innerWidth < 1025) {
		// medium threshold
		vpThreshold = 0.2;
	} else if (window.innerWidth < 641) {
		// small threshold
		vpThreshold = 0.15;
	}

	let firstObserve = true;

	const observer = new IntersectionObserver((observerEntries) => {
		const targetEntry = observerEntries[0] || null;

		if (!targetEntry) {
			observer.disconnect();
			return;
		}

		if ((firstObserve && targetEntry.boundingClientRect.top < 0) || targetEntry.intersectionRatio > 0) {
			targetEntry.target.classList.remove('block-animate');
			observer.disconnect();
		}

		firstObserve = false;
	}, {
		threshold: vpThreshold
	});

	observer.observe(el);

	return observer;
}

class BlockAnimationWatcher {
	constructor(watchEls) {
		this.observer = null;
		this.watchEls = watchEls;

		this.observers = [];

		this.init();
	}

	async init() {
		try {
			const ioAvailable = await loadIntersectionObserver();

			for (let i = 0; i < this.watchEls.length; i++) {
				this.observers.push(createElementObserver(this.watchEls[i]));
			}
		} catch (err) {
			console.error(err);
		}
	}
}

export default BlockAnimationWatcher;
