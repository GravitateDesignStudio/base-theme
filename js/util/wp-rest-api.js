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

	const jsonRes = await res.json();

	if (!res.ok) {
		throw { headers: res.headers, body: jsonRes };
	}

	return { headers: res.headers, body: jsonRes };
}

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
