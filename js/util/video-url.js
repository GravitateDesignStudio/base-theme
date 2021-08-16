/**
 * @typedef {Object} ParsedURLObject
 * @property {string} protocol
 * @property {string} host
 * @property {string} hostname
 * @property {string} port
 * @property {string} pathname
 * @property {string} query
 * @property {Object} queryObject
 * @property {string} hash
 */

/**
 * Returns an object with parsed parts of the specified URL
 *
 * @param {string} url The URL to parse
 * @returns {ParsedURLObject} The parsed URL object
 */
function parseURL(url) {
	const parser = document.createElement('a');
	const searchObject = {};
	let queries = [];
	let split = '';

	parser.href = url;

	if (parser.search) {
		queries = parser.search.replace(/^\?/, '').split('&');

		for (let i = 0; i < queries.length; i++) {
			split = queries[i].split('=');

			if (split.length === 2) {
				const [key, value] = split;

				searchObject[key] = value;
			}
		}
	}

	return {
		protocol: parser.protocol,
		host: parser.host,
		hostname: parser.hostname,
		port: parser.port,
		pathname: parser.pathname,
		query: parser.search,
		queryObject: searchObject,
		hash: parser.hash
	};
}

/**
 * Reform a URL with the properties of a parsed URL object
 *
 * @param {ParsedURLObject} parts The parsed URL object to refrom the URL from
 * @returns {string} The reformed URL
 */
function reformURL(parts) {
	let url = parts.protocol || '';

	url += '//';
	url += parts.hostname || parts.host || '';

	if ('port' in parts && parts.port) {
		url += ':' + parts.port;
	}

	url += parts.pathname || '';

	if ('queryObject' in parts) {
		const str = [];

		Object.keys(parts.queryObject).forEach((key) => {
			str.push(encodeURIComponent(key) + '=' + encodeURIComponent(parts.queryObject[key]));
		});

		parts.query = '?' + str.join('&');
	}

	if ('query' in parts) {
		url += parts.query;
	}

	return url;
}

/**
 * Return the embed URL for a YouTube, Vimeo, or Wistia video
 *
 * @param {string} url
 * @returns {string}
 */
export function getVideoEmbedURL(url) {
	const urlParts = parseURL(url);

	if (urlParts.hostname.indexOf('youtube.com') !== -1) {
		urlParts.queryObject.rel = 0;
		urlParts.queryObject.showinfo = 0;
		urlParts.queryObject.autoplay = 1;

		if ('v' in urlParts.queryObject) {
			// check for a "v=?" query param
			urlParts.pathname = `/embed/${urlParts.queryObject.v}`;

			delete urlParts.queryObject.v;
		} else if (urlParts.pathname.indexOf('/embed/') !== -1) {
			// check for "/embed/" in the path
			const pathParts = urlParts.pathname.split('/');

			urlParts.pathname = `/embed/${pathParts[pathParts.length - 1]}`;
		}
	} else if (urlParts.hostname.indexOf('youtu.be') !== -1) {
		urlParts.queryObject.rel = 0;
		urlParts.queryObject.showinfo = 0;
		urlParts.queryObject.autoplay = 1;

		urlParts.hostname = 'www.youtube.com';
		urlParts.pathname = `/embed/${urlParts.pathname.substr(1)}`;
	} else if (urlParts.hostname.indexOf('vimeo.com') !== -1) {
		urlParts.queryObject.autoplay = 1;
		urlParts.queryObject.portrait = 0;
		urlParts.queryObject.title = 0;
		urlParts.queryObject.byline = 0;

		if (urlParts.hostname !== 'player.vimeo.com') {
			const vimeoId = urlParts.pathname.substr(1).split('/')[0];

			urlParts.hostname = 'player.vimeo.com';
			urlParts.pathname = `/video/${vimeoId}`;
		}
	} else if (urlParts.hostname.indexOf('wistia.com') !== -1) {
		urlParts.queryObject.autoPlay = 1;
		urlParts.queryObject.playerColor = '3852ad';
		urlParts.queryObject.videoFoam = 'true';
		urlParts.queryObject.playbar = 'true';
		urlParts.queryObject.settingsControl = 'true';

		let videoId = '';

		if ('wvideo' in urlParts.queryObject) {
			videoId = urlParts.queryObject.wvideo;

			delete urlParts.queryObject.wvideo;
		} else {
			const pathParts = urlParts.pathname.split('/');

			videoId = pathParts[pathParts.length - 1];
		}

		urlParts.hostname = 'fast.wistia.net';
		urlParts.pathname = `/embed/iframe/${videoId}`;
	}

	return reformURL(urlParts);
}
