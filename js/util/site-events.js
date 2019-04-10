class SiteEvents {
	static get IMAGEBUDDY_TRIGGER_UPDATE() {
		return 'imagebuddy-trigger-update';
	}

	static get MODAL_VIDEO_OPEN() {
		return 'modal-video-open';
	}

	static imageBuddyUpdate(opts = {}) {
		jQuery(window).trigger(this.IMAGEBUDDY_TRIGGER_UPDATE, opts);
	}

	static modalVideoOpen(videoUrl) {
		jQuery(window).trigger(this.MODAL_VIDEO_OPEN, videoUrl);
	}
}

export default SiteEvents;
