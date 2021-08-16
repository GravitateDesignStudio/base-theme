/**
 * Emitter class that can be used when a pub-sub design pattern is needed
 */
class Emitter {
	/**
	 * Emitter constructor
	 */
	constructor() {
		this.emitterKey = 'emitter_' + Math.random().toString(36).substr(2, 9);

		window[this.emitterKey] = [];
	}

	/**
	 * Add an event listener by name
	 *
	 * @param {string} event The event name
	 * @param {function} handler A callback function that is run when the event is dispatched
	 * @param {Object} context An optional context that will be bound to the handler callback
	 */
	on(event, handler, context) {
		if (typeof context === 'undefined') {
			context = handler;
		}

		window[this.emitterKey].push({ event: event, handler: handler.bind(context) });
	}

	/**
	 * Trigger an event by name
	 *
	 * @param {string} event The event name
	 * @param {any} args Arguments that will be sent to the respective event handlers
	 */
	emit(event, args) {
		window[this.emitterKey].forEach((topic) => {
			if (topic.event === event) {
				topic.handler(args);
			}
		});
	}
}

export default Emitter;
