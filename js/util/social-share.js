/*
* Open a Facebook share dialog
*/
export function openFacebookShare() {
	window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), 'facebookShare', 'width=626,height=436');
}

/*
* Open a LinkedIn share dialog
*/
export function openLinkedInShare(opts = {}) {
	opts.title = opts.title || '';

	window.open(`https://www.linkedin.com/shareArticle?url=${encodeURIComponent(window.location.href)}&title=${encodeURIComponent(opts.title)}`, 'linkedinShare', 'width=626,height=436');
}

/*
* Open a Twitter share dialog
*/
export function openTwitterShare(opts = {}) {
	opts.title = opts.title || '';
	opts.twitterUsername = opts.twitterUsername || '';

	const twitterParts = [opts.title];

	if (opts.twitterUsername) {
		twitterParts.push(`@${opts.twitterUsername}`);
	}

	twitterParts.push(window.location.href);

	const twitterUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(twitterParts.join(' '));

	window.open(twitterUrl, 'twitterShare', 'width=626,height=436');
}
