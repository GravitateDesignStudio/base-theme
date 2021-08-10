class EmblaAutoplay {
	constructor(embla, interval) {
		this.emblaInstance = embla;
		this.interval = interval;
		this.timer = 0;

		if (this.emblaInstance) {
			this.emblaInstance.on('init', this.play);
			this.emblaInstance.on('pointerDown', this.stop);
		}
	}

	play = () => {
		this.stop();

		requestAnimationFrame(() => {
			this.timer = window.setTimeout(this.next, this.interval);
		});
	};

	stop = () => {
		window.clearTimeout(this.timer);

		this.timer = 0;
	};

	next = () => {
		if (this.emblaInstance.canScrollNext()) {
			this.emblaInstance.scrollNext();
		} else {
			this.emblaInstance.scrollTo(0);
		}

		this.play();
	};
}

export default EmblaAutoplay;
