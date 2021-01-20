import { wpAPIget } from '../util/wp-rest-api';
import LoadMore from '../components/archive-load-more';
import SiteEvents, { SiteEventNames } from '../util/site-events';

class ArchiveBlog {
	constructor(el) {
		this.el = el;
		this.cardsContainer = this.el.querySelector('[data-load-more-target]');
		this.loadMoreContainer = this.el.querySelector('.archive__load-more-container');

		this.loadMore = this.loadMoreContainer ? new LoadMore(this.loadMoreContainer) : null;

		if (this.loadMore) {
			this.loadMore.on('load', () => this.loadMorePosts());
		}
	}

	async loadMorePosts() {
		const nextPage = parseInt(this.cardsContainer.getAttribute('data-current-page'), 10) + 1;
		const totalPages = parseInt(this.cardsContainer.getAttribute('data-total-pages'), 10);
		const postsPerPage = parseInt(this.cardsContainer.getAttribute('data-posts-per-page'), 10);

		if (nextPage > totalPages) {
			this.loadMore.setVisible(false);
			return;
		}

		this.loadMore.setLoading(true);

		try {
			const res = await wpAPIget('wp/v2/posts', { page: nextPage, per_page: postsPerPage });
			const postsHTML = res.body.map((post) => post.card_markup);

			const addMarkup = postsHTML.map(
				(post) => `
				<div class="columns">
					${post}
				</div>`
			);

			this.cardsContainer.insertAdjacentHTML('beforeend', addMarkup.join(''));
			this.cardsContainer.setAttribute('data-current-page', nextPage);

			if (nextPage >= totalPages) {
				this.loadMore.setVisible(false);
			}

			SiteEvents.publish(SiteEventNames.IMAGEBUDDY_TRIGGER_UPDATE);
		} catch (err) {
			console.error(err);
		}

		this.loadMore.setLoading(false);
	}
}

export default ArchiveBlog;
