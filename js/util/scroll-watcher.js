function getScrollTop() {
	const scrollEl = document.scrollingElement || document.documentElement;

	return scrollEl.scrollTop;
}

class ScrollWatcher {
	constructor(scrollFunc) {
		this.curScrollPos = getScrollTop();
		this.prevScrollPos = 0;
		this.documentHeight = document.body.clientHeight;
		this.windowHeight = window.outerHeight;

		this.scrollFunc = typeof scrollFunc === 'function' ? scrollFunc : null;
		this.isTicking = false;

		if (this.scrollFunc) {
			document.addEventListener('scroll', this.scrollHandler.bind(this));

			this.scrollFunc({
				curScrollPos: this.curScrollPos,
				prevScrollPos: this.prevScrollPos,
				documentHeight: this.documentHeight,
				windowHeight: this.windowHeight
			});
		}
	}

	scrollHandler() {
		// is this working?
		if (this.isTicking) {
			return;
		}

		this.isTicking = true;

		requestAnimationFrame(() => {
			this.prevScrollPos = this.curScrollPos;
			this.curScrollPos = getScrollTop();

			if (this.scrollFunc) {
				this.scrollFunc({
					curScrollPos: this.curScrollPos,
					prevScrollPos: this.prevScrollPos,
					documentHeight: document.body.clientHeight,
					windowHeight: window.innerHeight
				});
			}

			this.isTicking = false;
		});
	}

	static defaultCallback(params, threshold = 100) {
		const htmlEl = document.querySelector('html');

		// remove all classes if we're below the threshold
		if (params.curScrollPos <= threshold) {
			htmlEl.classList.remove('scrolled');
			htmlEl.classList.remove('scroll-up');
			htmlEl.classList.remove('scroll-down');
			htmlEl.classList.remove('scroll-bottom');

			return;
		}

		htmlEl.classList.add('scrolled');

		// add/remove 'scroll-bottom' class
		if (params.curScrollPos >= (params.documentHeight - params.windowHeight)) {
			htmlEl.classList.add('scroll-bottom');
		} else {
			htmlEl.classList.remove('scroll-bottom');
		}

		// add/remove 'scroll-up' and 'scroll-down' classes
		if (params.curScrollPos > params.prevScrollPos) {
			htmlEl.classList.remove('scroll-up');
			htmlEl.classList.add('scroll-down');
		} else {
			htmlEl.classList.remove('scroll-down');
			htmlEl.classList.add('scroll-up');
		}
	}
}

export default ScrollWatcher;
