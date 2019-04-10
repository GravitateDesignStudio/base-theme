function getEventDelegateTriggerNode(targetNode, className) {
	const $target = jQuery(targetNode);

	if ($target.hasClass(className)) {
		return targetNode;
	}

	const $parents = $target.parents(`.${className}`);

	return $parents.length ? $parents.first().get(0) : null;
}

function setupEventDelegators(parentSelector, events = {}) {
	const $parent = jQuery(parentSelector);

	if (!$parent) {
		return;
	}

	$parent.on('click', (e) => {
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
