const BannerVideo = (function ($) {
	return class {
		constructor($el) {
			this.$el = $el;
			this.$bannerParent = this.$el.parents('.banner').first();
			this.currentSize = '';

			// bind functions
			this.catchPlaybackError = this.catchPlaybackError.bind(this);
			this.chooseVideoAndPoster = this.chooseVideoAndPoster.bind(this);

			// choose initial video and poster
			this.chooseVideoAndPoster();

			// catch playback errors
			this.$el.on('error abort', this.catchPlaybackError);

			// load a new video on resize if needed
			$(window).resize(this.chooseVideoAndPoster);
		}

		setVideoAutoplaySupport(isSupported, removeEl = false) {
			const htmlEl = document.querySelector('html');

			if (isSupported) {
				htmlEl.classList.add('video-autoplay-support');
				htmlEl.classList.remove('no-video-autoplay-support');
			} else {
				htmlEl.classList.remove('video-autoplay-support');
				htmlEl.classList.add('no-video-autoplay-support');
			}

			if (removeEl) {
				this.$el.remove();
			}
		}

		catchPlaybackError() {
			// NOTE: Safari will report 'stalled' even when the video is not stalled
			// do not listen for this event
			this.setVideoAutoplaySupport(false, false);
		}

		chooseVideoAndPoster() {
			const bannerWidth = $(window).width();
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
					videoUrl = this.$el.attr('data-video-desktop');
					posterUrl = this.$el.attr('data-poster-desktop');
					break;

				case 'tablet':
					videoUrl = this.$el.attr('data-video-tablet');
					posterUrl = this.$el.attr('data-poster-tablet');
					break;

				case 'mobile':
					videoUrl = this.$el.attr('data-video-mobile');
					posterUrl = this.$el.attr('data-poster-mobile');
					break;

				default:
					break;
			}

			// ensure we have a video URL
			if (videoUrl) {
				// pause the current video, set/load the new URL, and begin playback
				const videoEl = this.$el.get(0);

				videoEl.pause();

				this.$el.attr('src', videoUrl);
				this.$el.attr('poster', posterUrl);

				videoEl.load();
				videoEl.play();
			} else {
				// fallback to banner image for this size if no video URL is set
				this.setVideoAutoplaySupport(false);
			}
		}
	};
})(jQuery);

export default BannerVideo;
