import { isIE } from './browsers';

/**
 * Checks if the specified URL is either an external URL or a PDF file
 *
 * @param {string} url
 * @returns {boolean}
 */
function isExternalURL(url) {
	const firstCharValid = ['/', '#', '?'].indexOf(url.charAt(0)) === -1;
	const isDifferentHost = new RegExp(`/${window.location.host}/`).test(url) === false;
	const notEmail = url.slice(0, 7) !== 'mailto:';

	const validExternalURL = isDifferentHost && firstCharValid && notEmail;
	const isPDF = url.indexOf('.pdf') > 0;

	return validExternalURL || isPDF;
}

/**
 * Add target="_blank" and rel="noopener noreferrer" to links that are external.
 * Will also force PDF's to open in new tabs and adds an "external-link" class.
 *
 * @param {Object} opts
 */
export function processExternalLinks(opts = {}) {
	const attributes = opts.attributes || { target: '_blank', rel: 'noopener noreferrer' };
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

/**
 * Scroll to the specified element with an optional offset
 *
 * @param {HTMLElement|string} selector
 * @param {number} offset
 */
export function scrollTo(selector, offset) {
	let element = null;

	if (typeof selector === 'string') {
		element = document.querySelector(selector);
	} else {
		element = selector;
	}

	if (!element) {
		return;
	}

	if (typeof offset === 'undefined') {
		offset = 0;
	}

	const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
	const elPosFromTop = element.getBoundingClientRect().top + scrollTop - offset;

	if (isIE()) {
		window.scroll(0, elPosFromTop);
	} else {
		window.scroll({
			top: elPosFromTop,
			left: 0,
			behavior: 'smooth'
		});
	}
}

/**
 * Copy the specified text to the clipboard
 *
 * @param {string} copyText
 */
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
