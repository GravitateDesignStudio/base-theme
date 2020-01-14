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

export function loadSwiper() {
	return new Promise((resolve, reject) => {
		if (typeof window.Swiper !== 'undefined') {
			resolve(window.Swiper);
		}

		const existingInstance = getLoadedDependency('swiper');

		if (existingInstance) {
			resolve(existingInstance);
		}

		import(/* webpackChunkName: "swiper" */ 'swiper').then((Swiper) => {
			setLoadedDependency('swiper', Swiper.default);
			resolve(Swiper.default);
		}).catch((err) => {
			reject(err);
		});
	});
}

export function loadIntersectionObserver() {
	return new Promise((resolve, reject) => {
		if (typeof window.IntersectionObserver !== 'undefined') {
			resolve(true);
		}

		import(/* webpackChunkName: "intersection-observer" */ 'intersection-observer').then(() => {
			resolve(true);
		}).catch((err) => {
			reject(err);
		});
	});
}
