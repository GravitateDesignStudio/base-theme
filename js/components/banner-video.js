class BannerVideo {
	constructor(el) {
		this.el = el;
		this.bannerParentEl = this.el.closest('.banner');
		this.currentSize = '';

		// choose initial video and poster
		this.chooseVideoAndPoster();

		// catch playback errors
		this.el.addEventListener('error', this.catchPlaybackError);
		this.el.addEventListener('abort', this.catchPlaybackError);

		// load a new video on resize if needed
		window.addEventListener('resize', () => this.chooseVideoAndPoster());
	}

	setVideoAutoplaySupport(isSupported, removeEl = false) {
		const htmlEl = document.documentElement;

		if (isSupported) {
			htmlEl.classList.add('video-autoplay-support');
			htmlEl.classList.remove('no-video-autoplay-support');
		} else {
			htmlEl.classList.remove('video-autoplay-support');
			htmlEl.classList.add('no-video-autoplay-support');
		}

		if (removeEl) {
			this.el.remove();
		}
	}

	catchPlaybackError = () => {
		// NOTE: Safari will report 'stalled' even when the video is not stalled
		// do not listen for this event
		this.setVideoAutoplaySupport(false, false);
	};

	chooseVideoAndPoster = () => {
		const bannerWidth = window.innerWidth;
		let newSize = '';

		// figure out which device size we're looking at
		if (bannerWidth >= 1024) {
			newSize = 'desktop';
		} else if (bannerWidth >= 641) {
			newSize = 'tablet';
		} else {
			newSize = 'mobile';
		}

		// no need to make changes if the device size hasn't changed
		if (this.currentSize === newSize) {
			return;
		}

		this.currentSize = newSize;

		// select the video and poster URLs to use
		let videoUrl = '';
		let posterUrl = '';

		switch (this.currentSize) {
			case 'desktop':
				videoUrl = this.el.getAttribute('data-video-desktop');
				posterUrl = this.el.getAttribute('data-poster-desktop');
				break;

			case 'tablet':
				videoUrl = this.el.getAttribute('data-video-tablet');
				posterUrl = this.el.getAttribute('data-poster-tablet');
				break;

			case 'mobile':
				videoUrl = this.el.getAttribute('data-video-mobile');
				posterUrl = this.el.getAttribute('data-poster-mobile');
				break;

			default:
				break;
		}

		// ensure we have a video URL
		if (videoUrl) {
			// pause the current video, set/load the new URL, and begin playback
			this.el.pause();

			this.el.setAttribute('src', videoUrl);
			this.el.setAttribute('poster', posterUrl);

			this.el.load();
			this.el.play();
		} else {
			// fallback to banner image for this size if no video URL is set
			this.setVideoAutoplaySupport(false);
		}
	};
}

export default BannerVideo;
