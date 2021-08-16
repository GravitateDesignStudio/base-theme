/**
 * Returns the loaded dependency object by name or null if no dependency has
 * been loaded
 *
 * @param {string} depName The dependency name
 * @returns Object|null
 */
function getLoadedDependency(depName) {
	if (typeof window.appDependencies === 'undefined') {
		window.appDependencies = {};
	}

	if (Object.keys(window.appDependencies).indexOf(depName) === -1) {
		return null;
	}

	return window.appDependencies[depName];
}

/**
 * Set a loaded dependency object by name
 *
 * @param {string} depName The dependency name
 * @param {Object} loadedObject The dependency object
 */
function setLoadedDependency(depName, loadedObject) {
	if (typeof window.appDependencies === 'undefined') {
		window.appDependencies = {};
	}

	window.appDependencies[depName] = loadedObject;
}

/**
 * Load the Embla Carousel library. Returns a promise with the library object
 * when successful.
 *
 * @returns {Promise<object>}
 */
export function loadEmblaCarousel() {
	return new Promise((resolve, reject) => {
		if (typeof window.EmblaCarousel !== 'undefined') {
			resolve(window.EmblaCarousel);
		}

		const existingInstance = getLoadedDependency('embla-carousel');

		if (existingInstance) {
			resolve(existingInstance);
		}

		import(/* webpackChunkName: "embla-carousel" */ 'embla-carousel')
			.then((Embla) => {
				setLoadedDependency('embla-carousel', Embla.default);
				resolve(Embla.default);
			})
			.catch((err) => {
				reject(err);
			});
	});
}

/**
 * Load the Intersection Observer polyfill library. Returns a promise with the
 * library object when successful.
 *
 * @returns {Promise<object>}
 */
export function loadIntersectionObserver() {
	return new Promise((resolve, reject) => {
		if (typeof window.IntersectionObserver !== 'undefined') {
			resolve(true);
		}

		import(/* webpackChunkName: "intersection-observer" */ 'intersection-observer')
			.then(() => {
				resolve(true);
			})
			.catch((err) => {
				reject(err);
			});
	});
}
