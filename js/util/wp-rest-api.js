function getURL(endpoint = '') {
	const base = (typeof window.apiSettings !== 'undefined') ? window.apiSettings.base : '/wp-json/';

	return base + endpoint;
}

export function getPostsHTML(opts = {}) {
	const params = Object.assign({
		page: 1,
		per_page: 8
	}, opts);

	return new Promise((resolve, reject) => {
		jQuery.get(getURL('wp/v2/posts'), params)
			.done((data) => {
				if (!Array.isArray(data)) {
					reject(new Error('returned data is not an array'));
				} else {
					const postsHTML = data.map(post => post.card_markup);

					resolve(postsHTML);
				}
			})
			.fail((err) => {
				reject(err);
			});
	});
}

export function getSearchResultCardsHTML(opts = {}) {
	const params = Object.assign({
		page: 1,
		per_page: 8,
		search: ''
	}, opts);

	return new Promise((resolve, reject) => {
		jQuery.get(getURL('wp/v2/search'), params)
			.done((data) => {
				if (!Array.isArray(data)) {
					reject(new Error('returned data is not an array'));
				} else {
					const postsHTML = data.map(post => post.card_markup);

					resolve(postsHTML);
				}
			})
			.fail((err) => {
				reject(err);
			});
	});
}
