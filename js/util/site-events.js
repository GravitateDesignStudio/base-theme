/**
 * A pub-sub style pattern used for common site events
 */
class SiteEvents {
	/**
	 * SiteEvents constructor
	 */
	constructor() {
		this.observers = {};
	}

	/**
	 * Subscribe to the specified event and trigger a callback when the event
	 * has been dispatched
	 *
	 * @param {string} eventName The event name to subscribe to
	 * @param {function} callback The callback triggered when the specified event has been dispatched
	 */
	subscribe(eventName, callback) {
		if (typeof callback !== 'function') {
			return;
		}

		if (!Array.isArray(this.observers[eventName])) {
			this.observers[eventName] = [];
		}

		this.observers[eventName].push(callback);
	}

	/**
	 * Unsubscribe a listener from the specified event
	 *
	 * @param {string} eventName The event name to unsubscribe from
	 * @param {function} callback The callback function previously subscribed to the specified event
	 */
	unsubscribe(eventName, callback) {
		if (!Array.isArray(this.observers[eventName])) {
			return;
		}

		this.observers[eventName] = this.observers[eventName].filter(
			(observer) => observer !== callback
		);
	}

	/**
	 * Publish (dispatch) an event by name with values
	 *
	 * @param {string} eventName The event name to publish
	 * @param {any} values Values that will be sent to all subscriber callbacks
	 */
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

/**
 * Constant values for common site event types
 */
export const SiteEventNames = {
	IMAGEBUDDY_TRIGGER_UPDATE: 'imagebuddy-trigger-update',
	MODAL_VIDEO_OPEN: 'modal-video-open'
};

export default new SiteEvents();
