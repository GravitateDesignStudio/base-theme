import { getPostsHTML } from '../util/wp-rest-api';
import LoadMore from '../components/archive-load-more';
import SiteEvents from '../util/site-events';

class ArchiveBlog {
	constructor($el) {
		this.$el = $el;
		this.$cardsContainer = this.$el.find('[data-load-more-target]').first();

		this.loadMore = new LoadMore(this.$el.find('.archive__load-more-container').first());

		this.loadMore.on('load', () => {
			this.loadMorePosts();
		});
	}

	loadMorePosts() {
		const nextPage = parseInt(this.$cardsContainer.attr('data-current-page'), 10) + 1;
		const totalPages = parseInt(this.$cardsContainer.attr('data-total-pages'), 10);
		const postsPerPage = parseInt(this.$cardsContainer.attr('data-posts-per-page'), 10);

		if (nextPage > totalPages) {
			this.loadMore.setVisible(false);
			return;
		}

		this.loadMore.setLoading(true);

		getPostsHTML({
			page: nextPage,
			per_page: postsPerPage
		}).then((postsHTML) => {
			const addMarkup = postsHTML.map(post => `
				<div class="columns">
					${post}
				</div>
			`);

			this.$cardsContainer.append(addMarkup.join(''));
			this.$cardsContainer.attr('data-current-page', nextPage);

			if (nextPage >= totalPages) {
				this.loadMore.setVisible(false);
			}

			SiteEvents.imageBuddyUpdate();
		}).catch((err) => {
			console.error(err);
		}).finally(() => {
			this.loadMore.setLoading(false);
		});
	}
}

export default ArchiveBlog;
