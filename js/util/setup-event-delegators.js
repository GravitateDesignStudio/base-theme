function getEventDelegateTriggerNode(targetNode, className) {
	if (!targetNode) {
		return null;
	}

	return targetNode.classList.contains(className)
		? targetNode
		: targetNode.closest(`.${className}`);
}

function setupEventDelegators(parentSelector, events = {}) {
	const parentEl = document.querySelector(parentSelector);

	if (!parentEl) {
		return;
	}

	parentEl.addEventListener('click', (e) => {
		const selectors = Object.keys(events);
		let interceptEvent = false;

		selectors.forEach((selector) => {
			const triggerNode = getEventDelegateTriggerNode(e.target, selector);

			if (!triggerNode) {
				return;
			}

			interceptEvent = true;

			const callbackFunc = events[selector];

			if (typeof callbackFunc === 'function') {
				callbackFunc(triggerNode);
			}
		});

		if (interceptEvent) {
			e.preventDefault();
		}
	});
}

export default setupEventDelegators;
