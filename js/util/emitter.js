class Emitter {
	constructor() {
		this.emitterKey = 'emitter_' + Math.random().toString(36).substr(2, 9);

		window[this.emitterKey] = [];
	}

	on(event, handler, context) {
		if (typeof context === 'undefined') {
			context = handler;
		}

		window[this.emitterKey].push({ event: event, handler: handler.bind(context) });
	}

	emit(event, args) {
		window[this.emitterKey].forEach((topic) => {
			if (topic.event === event) {
				topic.handler(args);
			}
		});
	}
}

export default Emitter;
