/* global jQuery */

// This '@babel/polyfill' module is necessary for async/await functionality
import '@babel/polyfill';

import objectFitImages from 'object-fit-images';
import ImageBuddy from 'imagebuddy';

import ThemeWelcome from './components/testing/theme-welcome';
import SiteHeader from './components/site-header';
import BannerVideo from './components/banner-video';

import ArchiveBlog from './templates/archive-blog';
import SearchResults from './templates/search-results';

import { processExternalLinks } from './util/general-util';
import ScrollWatcher from './util/scroll-watcher';
import SiteEvents from './util/site-events';
import blockAnimationWatcher from './util/block-animation-watcher';

const App = (function ($) {
	return class {
		constructor() {
			this.instances = {
				components: {
					themeWelcome: null,
					siteHeader: null,
					bannerVideo: null
				},
				templates: {
					archiveBlog: null,
					searchResults: null
				},
				blocks: {},
				scrollWatcher: null,
				imageBuddy: null,
				blockAnimationWatcher: null
			};

			this.init();
			this.initComponents();
			this.initTemplates();
			this.initBlocks();
		}

		init() {
			processExternalLinks({
				target: '_blank',
				rel: 'noopener'
			});

			this.scrollWatcher = new ScrollWatcher((params) => {
				ScrollWatcher.defaultCallback(params, 100);
			});

			// initialize ImageBuddy and setup events
			this.instances.imageBuddy = new ImageBuddy({
				lazyLoad: true
				// debug: true
			});

			ImageBuddy.on('image-loaded', (imgEl) => {
				objectFitImages(imgEl);
			});

			$(window).on(SiteEvents.IMAGEBUDDY_TRIGGER_UPDATE, (e, opts) => {
				const updateOpts = opts || {};

				this.instances.imageBuddy.update(updateOpts);
			});

			// ObjectFitImages: only act upon images that have a valid src attribute
			// Note: use the 'object-fit-polyfill' SCSS mixin to create the necessary 'font-family' attribute
			objectFitImages('img:not([src^="data:"])');
		}

		async initComponents() {
			// theme welcome -- this can be removed during development
			const $themeWelcomeEls = $('.theme-welcome');

			if ($themeWelcomeEls.length) {
				try {
					const Swiper = await import(/* webpackChunkName: "swiper" */ 'swiper');
					
					this.instances.themeWelcome = new ThemeWelcome($themeWelcomeEls.first(), Swiper.default);
				} catch (err) {
					console.error('Swiper dynamic import failed', err);
				}
			}

			// site header
			const $siteHeaderEls = $('.site-header');

			if ($siteHeaderEls.length) {
				this.instances.components.siteHeader = new SiteHeader($siteHeaderEls.first());
			}

			// banner cover video
			const $bannerCoverVideo = $('.banner__cover-video');

			if ($bannerCoverVideo.length) {
				this.instances.components.bannerVideo = new BannerVideo($bannerCoverVideo);
			}
		}

		initTemplates() {
			// blog archive
			const $archiveBlogEl = $('.main-content__archive-blog').first();

			if ($archiveBlogEl) {
				this.instances.templates.archiveBlog = new ArchiveBlog($archiveBlogEl);
			}

			// search results
			const $searchResultsEl = $('.main-content__search').first();

			if ($searchResultsEl) {
				this.instances.templates.searchResults = new SearchResults($searchResultsEl);
			}
		}

		initBlocks() {
			// initialize blocks here

			// setup block animation watcher
			const animateBlocks = document.querySelectorAll('.block-animate');

			if (animateBlocks && animateBlocks.length) {
				this.instances.blockAnimationWatcher = new BlockAnimationWatcher(animateBlocks);
			}
		}
	};
})(jQuery);

export default App;
