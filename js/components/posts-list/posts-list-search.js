import PostsList from './posts-list';
import { queryArgsAsMap, getUrlWithQueryArgs, getUrlQueryArg } from '../../util/url-util';

class PostsListSearch extends PostsList {
	constructor(el) {
		super(el);

		this.setEndpoint('wp/v2/search');
		this.setCardContainerClasses('columns small-12');

		this.formSearchEl = this.el.querySelector('form#form_filter_search');

		this.filterSearch = getUrlQueryArg('s', '');

		if (this.loadMore) {
			this.loadMore.on('load', () => this.loadSearchResults());
		}

		if (this.formSearchEl) {
			this.formSearchEl.addEventListener('submit', (e) => {
				e.preventDefault();

				const inputEl = e.currentTarget.querySelector('input');

				if (inputEl) {
					this.filterSearch = inputEl.value.trim();

					this.updateURL();
					this.clearCardsContainer();
					this.loadSearchResults();
				}
			});
		}
	}

	loadSearchResults() {
		const config = {
			search: this.filterSearch
		};

		this.loadMorePosts(config);
	}

	updateURL() {
		const args = queryArgsAsMap();

		args.set('s', this.filterSearch);

		window.history.replaceState({}, '', getUrlWithQueryArgs(args));
	}
}

export default PostsListSearch;
