import { wpAPIget } from '../../util/wp-rest-api';
import LoadMore from '../archive-load-more';
import SiteEvents, { SiteEventNames } from '../../util/site-events';

class PostsList {
	/**
	 * PostsList constructor
	 *
	 * @param {HTMLElement} el The posts list element
	 */
	constructor(el) {
		this.el = el;
		this.cardsContainer = this.el.querySelector('[data-load-more-target]');
		this.loadMoreContainer = this.el.querySelector('.archive__load-more-container');
		this.noResultsContainer = this.el.querySelector('[data-no-results-container]');
		this.loadingSpinner = this.el.querySelector('.posts-list__loader-container');

		this.endpoint = 'wp/v2/posts';
		this.cardContainerClasses = 'columns small-12 medium-6 large-3';
		this.postDisplayFunc = (cardMarkup, postsList) => `
			<div class="${postsList.cardContainerClasses}">
				${cardMarkup}
			</div>
		`;
		this.loadMore = this.loadMoreContainer ? new LoadMore(this.loadMoreContainer) : null;

		if (this.loadMore) {
			const curPage = parseInt(this.cardsContainer.getAttribute('data-current-page'), 10);
			const totalPages = parseInt(this.cardsContainer.getAttribute('data-total-pages'), 10);

			this.loadMore.setVisible(curPage < totalPages);
		}
	}

	/**
	 * Set the API endpoint used by this post list
	 *
	 * @param {string} endpoint
	 */
	setEndpoint(endpoint) {
		this.endpoint = endpoint;
	}

	/**
	 * Set the card container classes used when new cards are added dynamically
	 *
	 * @param {string} cardContainerClasses
	 */
	setCardContainerClasses(cardContainerClasses) {
		this.cardContainerClasses = cardContainerClasses;
	}

	/**
	 * Set the callback function used to generate markup for cards that are added
	 * dynamically
	 *
	 * @param {function} postDisplayFunc
	 */
	setPostDisplayCallback(postDisplayFunc) {
		this.postDisplayFunc = postDisplayFunc;
	}

	/**
	 * Set the loading spinner visibility
	 *
	 * @param {boolean} showSpinner
	 */
	showLoadingSpinner(showSpinner) {
		if (!this.loadingSpinner) {
			return;
		}

		if (showSpinner) {
			this.loadingSpinner.classList.remove('hide');
		} else {
			this.loadingSpinner.classList.add('hide');
		}
	}

	/**
	 * Clear the cards container
	 */
	clearCardsContainer() {
		this.cardsContainer.innerHTML = '';

		this.cardsContainer.setAttribute('data-current-page', 0);
		this.cardsContainer.setAttribute('data-total-pages', 1);

		this.showLoadingSpinner(true);

		this.loadMore.setVisible(false);
	}

	/**
	 * Set the visibility of the "no results" container and message
	 *
	 * @param {boolean} show
	 */
	showNoResultsMessage(show) {
		if (!this.noResultsContainer) {
			return;
		}

		if (show) {
			this.noResultsContainer.classList.remove('hide');
		} else {
			this.noResultsContainer.classList.add('hide');
		}
	}

	/**
	 * Load additional posts for the post list
	 *
	 * @param {Object} addParams Additional parameters to add to the API request
	 */
	async loadMorePosts(addParams = {}) {
		if (!this.endpoint) {
			throw new Error('No endpoint provided for loadMorePosts');
		}

		const curPage = parseInt(this.cardsContainer.getAttribute('data-current-page'), 10);
		const nextPage = curPage + 1;
		const totalPages = parseInt(this.cardsContainer.getAttribute('data-total-pages'), 10);
		const postsPerPage = parseInt(this.cardsContainer.getAttribute('data-posts-per-page'), 10);

		this.showNoResultsMessage(false);

		if (nextPage > totalPages) {
			this.loadMore.setVisible(false);
			return;
		}

		const params = {
			page: nextPage,
			per_page: postsPerPage,
			...addParams
		};

		this.loadMore.setLoading(true);

		try {
			const res = await wpAPIget(this.endpoint, params);

			const newTotalPages =
				res.headers['x-wp-totalpages'] !== 'undefined'
					? parseInt(res.headers['x-wp-totalpages'], 10)
					: totalPages;

			this.showLoadingSpinner(false);

			if (!res.body.length && curPage === 0) {
				this.showNoResultsMessage(true);
			} else {
				const postsHTML = res.body.map((post) => post.card_markup);
				const addMarkup = postsHTML.map((post) => this.postDisplayFunc(post, this));

				this.cardsContainer.insertAdjacentHTML('beforeend', addMarkup.join(''));
			}

			this.cardsContainer.setAttribute('data-current-page', nextPage);
			this.cardsContainer.setAttribute('data-total-pages', newTotalPages);

			this.loadMore.setVisible(newTotalPages > nextPage);

			SiteEvents.publish(SiteEventNames.IMAGEBUDDY_TRIGGER_UPDATE);
		} catch (err) {
			this.showLoadingSpinner(false);

			console.error(err);
		}

		this.loadMore.setLoading(false);
	}
}

export default PostsList;
