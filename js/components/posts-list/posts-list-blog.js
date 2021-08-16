import PostsList from './posts-list';
import { queryArgsAsMap, getUrlWithQueryArgs, getUrlQueryArg } from '../../util/url-util';

class PostsListBlog extends PostsList {
	constructor(el) {
		super(el);

		this.setEndpoint('wp/v2/posts');
		this.setCardContainerClasses('columns small-12 medium-6 large-3');

		this.formSearchEl = this.el.querySelector('form#form_filter_search');
		this.filterCategoryEl = this.el.querySelector('select#filter_category');
		this.filterTagEl = this.el.querySelector('select#filter_tag');

		this.filterCategorySlug = getUrlQueryArg('category', '');
		this.filterTagSlug = getUrlQueryArg('tag', '');
		this.filterSearch = getUrlQueryArg('search', '');

		if (this.loadMore) {
			this.loadMore.on('load', () => this.loadBlogPosts());
		}

		if (this.formSearchEl) {
			this.formSearchEl.addEventListener('submit', (e) => {
				e.preventDefault();

				const inputEl = e.currentTarget.querySelector('input');

				if (inputEl) {
					this.filterSearch = inputEl.value.trim();

					this.updateURL();
					this.clearCardsContainer();
					this.loadBlogPosts();
				}
			});
		}

		if (this.filterCategoryEl) {
			this.filterCategoryEl.addEventListener('change', (e) => {
				this.filterCategorySlug = e.currentTarget.value ?? '';

				this.updateURL();
				this.clearCardsContainer();
				this.loadBlogPosts();
			});
		}

		if (this.filterTagEl) {
			this.filterTagEl.addEventListener('change', (e) => {
				this.filterTagSlug = e.currentTarget.value ?? '';

				this.updateURL();
				this.clearCardsContainer();
				this.loadBlogPosts();
			});
		}
	}

	loadBlogPosts() {
		const config = {
			category: this.filterCategorySlug,
			tag: this.filterTagSlug,
			search: this.filterSearch
		};

		this.loadMorePosts(config);
	}

	updateURL() {
		const args = queryArgsAsMap();

		if (this.filterCategorySlug) {
			args.set('category', this.filterCategorySlug);
		} else {
			args.delete('category');
		}

		if (this.filterTagSlug) {
			args.set('tag', this.filterTagSlug);
		} else {
			args.delete('tag');
		}

		if (this.filterSearch) {
			args.set('search', this.filterSearch);
		} else {
			args.delete('search');
		}

		window.history.replaceState({}, '', getUrlWithQueryArgs(args));
	}
}

export default PostsListBlog;
