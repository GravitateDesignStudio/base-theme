import { getSearchResultCardsHTML } from '../util/wp-rest-api';
import ArchiveLoadMore from '../components/archive-load-more';

class SearchResults {
	constructor($el) {
		this.$el = $el;
		this.$cardsContainer = this.$el.find('[data-load-more-target]').first();

		this.loadMore = new ArchiveLoadMore(this.$el.find('.archive__load-more-container').first());

		this.loadMore.on('load', () => {
			this.loadMorePosts();
		});
	}

	loadMorePosts() {
		const nextPage = parseInt(this.$cardsContainer.attr('data-current-page'), 10) + 1;
		const totalPages = parseInt(this.$cardsContainer.attr('data-total-pages'), 10);
		const postsPerPage = parseInt(this.$cardsContainer.attr('data-posts-per-page'), 10);
		const searchString = this.$cardsContainer.attr('data-search');

		if (nextPage > totalPages) {
			this.loadMore.setVisible(false);
			return;
		}

		this.loadMore.setLoading(true);

		getSearchResultCardsHTML({
			page: nextPage,
			per_page: postsPerPage,
			search: searchString
		}).then((postsHTML) => {
			const addMarkup = postsHTML.map(post => `
				<div class="row align-center">
					<div class="columns small-12 medium-10">
						${post}
					</div>
				</div>
			`);

			this.$cardsContainer.append(addMarkup.join(''));
			this.$cardsContainer.attr('data-current-page', nextPage);

			if (nextPage >= totalPages) {
				this.loadMore.setVisible(false);
			}
		}).catch((err) => {
			console.error(err);
		}).finally(() => {
			this.loadMore.setLoading(false);
		});
	}
}

export default SearchResults;
