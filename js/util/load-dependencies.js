function getLoadedDependency(depName) {
	if (typeof window.appDependencies === 'undefined') {
		window.appDependencies = {};
	}

	if (Object.keys(window.appDependencies).indexOf(depName) === -1) {
		return null;
	}

	return window.appDependencies[depName];
}

function setLoadedDependency(depName, loadedObject) {
	if (typeof window.appDependencies === 'undefined') {
		window.appDependencies = {};
	}

	window.appDependencies[depName] = loadedObject;
}

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
