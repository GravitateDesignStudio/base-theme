/**
 * Return a Map object with the current URL query parameters as values
 *
 * @returns {Map} a Map object containing the current URL query parameters
 */
export function queryArgsAsMap() {
	const partsMap = new Map();
	const parts = window.location.search.substring(1).split('&');

	parts.forEach((part) => {
		const [partName, partVal] = part.split('=');

		if (partName) {
			partsMap.set(partName, partVal);
		}
	});

	return partsMap;
}

/**
 * Re-assemble a URL with the query parameters from the specified Map object
 *
 * @param {Map} queryArgsMap The query args Map object
 * @returns {string} The assembled URL
 */
export function getUrlWithQueryArgs(queryArgsMap) {
	const baseUrl = window.location.origin + window.location.pathname;
	const parts = [];

	queryArgsMap.forEach((value, key) => {
		parts.push(`${encodeURIComponent(key)}=${encodeURIComponent(value)}`);
	});

	return parts.length ? `${baseUrl}?${parts.join('&')}` : baseUrl;
}

/**
 * Retrieve the value of a query parameter in the current URL with a default
 * specified in case it does not exist
 *
 * @param {string} name The query parameter name
 * @param {any} defaultValue The default value if it is not found
 * @returns {any}
 */
export function getUrlQueryArg(name, defaultValue = '') {
	const queryArgsMap = queryArgsAsMap();

	return queryArgsMap.get(name) ?? defaultValue;
}

/**
 * Return a re-assembled version of the current URL with the specified query
 * parameter added
 *
 * @param {string} name The query parameter name to add
 * @param {any} value The value
 * @returns {string} The current URL re-assembled with the specified query parameter added
 */
export function addUrlQueryArg(name, value) {
	const queryArgsMap = queryArgsAsMap();

	queryArgsMap.set(name, encodeURIComponent(value));

	return getUrlWithQueryArgs(queryArgsMap);
}

/**
 * Return a re-assembled version of the current URL with the specified query
 * parameter removed
 *
 * @param {string} name The query parameter name to remove
 * @returns {string} The current URL re-assembled with the specified query parameter removed
 */
export function removeUrlQueryArg(name) {
	const queryArgsMap = queryArgsAsMap();

	queryArgsMap.delete(name);

	return getUrlWithQueryArgs(queryArgsMap);
}
