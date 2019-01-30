function isExternalURL(url) {
	const firstCharValid = ['/', '#', '?'].indexOf(url.charAt(0)) === -1;
	const isDifferentHost = new RegExp(`/${window.location.host}/`).test(url) === false;
	const notEmail = url.slice(0, 7) !== 'mailto:';

	const validExternalURL = isDifferentHost && firstCharValid && notEmail;
	const isPDF = url.indexOf('.pdf') > 0;

	return validExternalURL || isPDF;
}

/*
* Add target="_blank" and rel="noopener" to links that are external
* Will also force PDF's to open in new tabs
* Adds "external-link" class
*/
export function processExternalLinks(opts = {}) {
	const attributes = opts.attributes || { target: '_blank', rel: 'noopener' };
	const anchors = document.querySelectorAll('a[href]');

	anchors.forEach((anchor) => {
		if (isExternalURL(anchor.href)) {
			Object.keys(attributes).forEach((attrName) => {
				anchor.setAttribute(attrName, attributes[attrName]);
			});

			anchor.classList.add('external-link');
		}
	});
}

/*
* Scrolling function to animate to a
* selector, with optional offset
*/
export function scrollTo(selector, offset) {
	let element;

	if (typeof selector === 'string') {
		element = jQuery(selector);
	} else {
		element = selector;
	}

	if (typeof offset === 'undefined') {
		offset = 0;
	}

	jQuery('html, body').animate({
		scrollTop: (element.offset().top - offset)
	}, 500);
}
