/**
 * Returns an ancestral (or direct) reference to a node specified by a classname
 * within the specified target node
 *
 * @param {HTMLElement} targetNode The current target DOM node from an event
 * @param {string} className The classname of the element to search for
 * @returns HTMLElement|null The element matching the specified classname or null if not found
 */
function getEventDelegateTriggerNode(targetNode, className) {
	if (!targetNode) {
		return null;
	}

	return targetNode.classList.contains(className)
		? targetNode
		: targetNode.closest(`.${className}`);
}

/**
 * Attach an event listener to a parent element that will listen for clicks on
 * specified child elements determined by their class names. Event delegation to
 * a parent prevents the need to conditionally attach event handlers to children
 * in areas that are populated dynamically. An example of this would be post cards
 * within post archives with lazy load functionality.
 *
 * @param {string} parentSelector The parent element selector string to attach an event listener to
 * @param {Object} events An object containing child element classes as property names and callbacks as values
 *
 * @example
 * setupEventDelegators('body', {
 *     'js--trigger-video-modal': (el) => {
 *         const videoUrl = el.href || '';
 *
 *         showVideoModal(videoUrl);
 *     }
 * })
 */
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
