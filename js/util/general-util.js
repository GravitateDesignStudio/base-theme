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

	Array.from(anchors).forEach((anchor) => {
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

	if (!element.length) {
		return;
	}

	if (typeof offset === 'undefined') {
		offset = 0;
	}

	jQuery('html, body').animate({
		scrollTop: (element.offset().top - offset)
	}, 500);
}

export function getVideoEmbedURL(videoUrl) {
	let embedUrl = videoUrl;

	if (videoUrl.indexOf('youtube.com') !== -1) {
		const videoId = new RegExp('[\\?&]v=([^&#]*)').exec(videoUrl);

		if (videoId && videoId[1]) {
			embedUrl = 'https://www.youtube.com/embed/' + videoId[1] + '?rel=0&wmode=transparent&autoplay=1&showinfo=0';
		}
	} else if (videoUrl.indexOf('vimeo.com') !== -1) {
		console.log('would process video.com url');
	}

	return embedUrl;
}

export function copyTextToClipboard(copyText) {
	const textArea = document.createElement('textarea');
	let copied = '';

	textArea.style.position = 'fixed';
	textArea.style.top = 0;
	textArea.style.left = 0;

	textArea.style.width = '2rem';
	textArea.style.height = '2rem';

	textArea.style.padding = 0;

	textArea.style.border = 'none';
	textArea.style.outline = 'none';
	textArea.style.boxShadow = 'none';

	textArea.value = copyText;

	document.body.appendChild(textArea);

	textArea.select();

	try {
		copied = document.execCommand('copy');

		if (document.execCommand('copy')) {
			copied = copyText;
		}
	} catch (err) {
		console.error(`unable to copy text: ${copyText}`);
	}

	document.body.removeChild(textArea);

	return copied;
}

export function getUrlQueryArg(name) {
	let retVal = null;
	const urlParser = document.createElement('a');

	urlParser.href = window.location.href;

	urlParser.search.substring(1).split('&').forEach((val) => {
		const [partName, partVal] = val.split('=');

		if (partName === name && partVal.length) {
			retVal = partVal;
		}
	});

	return retVal;
}

export function addUrlQueryArg(name, value) {
	let newUrl = window.location.href;

	newUrl += window.location.search ? '&' : '?';
	newUrl += `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;

	return newUrl;
}

export function removeUrlQueryArg(name) {
	const queryArgs = window.location.search.substring(1).split('&').filter((val) => {
		const [partName, partVal] = val.split('=');

		return partName !== name;
	});

	let newUrl = window.location.origin + window.location.pathname;

	if (queryArgs.length) {
		newUrl += `?${queryArgs.join('&')}`;
	}

	return newUrl;
}