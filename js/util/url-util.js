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

export function getUrlWithQueryArgs(queryArgsMap) {
	const baseUrl = window.location.origin + window.location.pathname;
	const parts = [];

	queryArgsMap.forEach((value, key) => {
		parts.push(`${encodeURIComponent(key)}=${encodeURIComponent(value)}`);
	});

	return parts.length ? `${baseUrl}?${parts.join('&')}` : baseUrl;
}

export function getUrlQueryArg(name, defaultValue = '') {
	const queryArgsMap = queryArgsAsMap();

	return queryArgsMap.get(name) ?? defaultValue;
}

export function addUrlQueryArg(name, value) {
	const queryArgsMap = queryArgsAsMap();

	queryArgsMap.set(name, encodeURIComponent(value));

	return getUrlWithQueryArgs(queryArgsMap);
}

export function removeUrlQueryArg(name) {
	const queryArgsMap = queryArgsAsMap();

	queryArgsMap.delete(name);

	return getUrlWithQueryArgs(queryArgsMap);
}
