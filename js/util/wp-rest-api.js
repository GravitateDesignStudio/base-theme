function getURL(endpoint = '') {
	const base = typeof window.apiSettings !== 'undefined' ? window.apiSettings.base : '/wp-json/';

	return base + endpoint;
}

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

	if (!res.ok) {
		throw new Error(res.json());
	}

	return res.json();
}
