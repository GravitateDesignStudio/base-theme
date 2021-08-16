/**
 * @typedef WpApiResponse
 * @description The promise object returned from a WP REST API request
 * @property {Object} headers An object representing the response headers
 * @property {Object} body An object representing the parsed body JSON
 */

/**
 * Converts a Fetch Headers iterator to an object
 *
 * @param {Headers} headers The Fetch Headers iterator
 * @returns {Object}
 */
function getHeadersAsObject(headers) {
	const headersObj = {};
	let result = headers.next();

	while (!result.done) {
		const [name, value] = result.value;

		headersObj[name] = value;

		result = headers.next();
	}

	return headersObj;
}

/**
 * Return the REST URL for the provided endpoint. Ex: wp/v2/posts
 *
 * @param {string} endpoint
 * @returns {string}
 */
function getURL(endpoint = '') {
	const base = typeof window.apiSettings !== 'undefined' ? window.apiSettings.base : '/wp-json/';

	return base + endpoint;
}

/**
 * Sends a GET request to the specified endpoint
 *
 * @param {string} endpoint
 * @param {Object} params
 * @param {RequestInit} fetchOpts
 * @returns {Promise<WpApiResponse>}
 */
export async function wpAPIget(endpoint, params = {}, fetchOpts = {}) {
	let apiURL = getURL(endpoint);

	if (Object.entries(params).length) {
		const paramsStr = new URLSearchParams(params).toString();
		apiURL = `${apiURL}?${paramsStr}`;
	}

	const res = await fetch(apiURL, {
		method: 'GET',
		cache: 'no-cache',
		credentials: 'include',
		...fetchOpts
	});

	const jsonRes = await res.json();

	if (!res.ok) {
		throw { headers: getHeadersAsObject(res.headers.entries()), body: jsonRes };
	}

	return { headers: getHeadersAsObject(res.headers.entries()), body: jsonRes };
}

/**
 * Sends a POST request to the specified endpoint
 *
 * @param {string} endpoint
 * @param {Object} params
 * @param {RequestInit} fetchOpts
 * @returns {Promise<WpApiResponse>}
 */
export async function wpAPIpost(endpoint, params = {}, fetchOpts = {}) {
	const formBody = Object.keys(params)
		.map((key) => encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
		.join('&');

	const fetchHeaders = fetchOpts.headers ?? {};

	delete fetchOpts.headers;

	const reqOpts = {
		method: 'POST',
		cache: 'no-cache',
		credentials: 'include',
		body: formBody,
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
			...fetchHeaders
		},
		...fetchOpts
	};

	const res = await fetch(getURL(endpoint), reqOpts);

	const jsonRes = await res.json();

	if (!res.ok) {
		throw { headers: res.headers, body: jsonRes };
	}

	return { headers: res.headers, body: jsonRes };
}
