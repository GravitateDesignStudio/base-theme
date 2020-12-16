class SiteEvents {
	constructor() {
		this.observers = {};
	}

	subscribe(eventName, callback) {
		if (typeof callback !== 'function') {
			return;
		}

		if (!Array.isArray(this.observers[eventName])) {
			this.observers[eventName] = [];
		}

		this.observers[eventName].push(callback);
	}

	unsubscribe(eventName, callback) {
		if (!Array.isArray(this.observers[eventName])) {
			return;
		}

		this.observers[eventName] = this.observers[eventName].filter(
			(observer) => observer !== callback
		);
	}

	publish(eventName, values = {}) {
		if (!Array.isArray(this.observers[eventName])) {
			return;
		}

		this.observers[eventName].forEach((observer) => {
			if (typeof observer === 'function') {
				observer(values);
			}
		});
	}
}

export const SiteEventNames = {
	IMAGEBUDDY_TRIGGER_UPDATE: 'imagebuddy-trigger-update',
	MODAL_VIDEO_OPEN: 'modal-video-open'
};

export default new SiteEvents();
