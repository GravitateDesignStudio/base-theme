import objectFitImages from 'object-fit-images';
import ImageBuddy from 'imagebuddy';

import ThemeWelcome from './components/testing/theme-welcome';
import SiteHeader from './components/site-header';
import BannerVideo from './components/banner-video';
import Modal from './components/modal';

import ArchiveBlog from './templates/archive-blog';
import SearchResults from './templates/search-results';

import { processExternalLinks } from './util/general-util';
import ScrollWatcher from './util/scroll-watcher';
import SiteEvents, { SiteEventNames } from './util/site-events';
import BlockAnimationWatcher from './util/block-animation-watcher';
import HelloBar from './util/hello-bar';

class App {
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
			blockAnimationWatcher: null,
			helloBar: null
		};

		this.init();
		this.initComponents();
		this.initTemplates();
		this.initBlocks();
		this.initSocialShare();
	}

	init() {
		processExternalLinks({
			target: '_blank',
			rel: 'noopener'
		});

		Modal.setDefaults({
			closeDuration: 400,
			closeButtonContent: `
				<svg xmlns="http://www.w3.org/2000/svg" class="modal__close-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			`.trim()
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

		SiteEvents.subscribe(SiteEventNames.IMAGEBUDDY_TRIGGER_UPDATE, (opts) => {
			this.instances.imageBuddy.update(opts || {});
		});

		this.instances.helloBar = new HelloBar();
		this.instances.helloBar.init();

		// ObjectFitImages: only act upon images that have a valid src attribute
		// Note: use the 'object-fit-polyfill' SCSS mixin to create the necessary 'font-family' attribute
		objectFitImages('img:not([src^="data:"])');
	}

	async initComponents() {
		// theme welcome
		// TODO: remove during development
		const themeWelcomeEl = document.querySelector('.theme-welcome');

		if (themeWelcomeEl) {
			this.instances.themeWelcome = new ThemeWelcome(themeWelcomeEl);
		}

		// site header
		const siteHeaderEl = document.querySelector('.site-header');

		if (siteHeaderEl) {
			this.instances.components.siteHeader = new SiteHeader(siteHeaderEl);
		}

		// banner cover video
		const bannerCoverVideoEl = document.querySelector('.banner__cover-video');

		if (bannerCoverVideoEl) {
			this.instances.components.bannerVideo = new BannerVideo(bannerCoverVideoEl);
		}
	}

	initTemplates() {
		// blog archive
		const archiveBlogEl = document.querySelector('.tmpl-archive-blog');

		if (archiveBlogEl) {
			this.instances.templates.archiveBlog = new ArchiveBlog(archiveBlogEl);
		}

		// search results
		const searchResultsEl = document.querySelector('.tmpl-search');

		if (searchResultsEl) {
			this.instances.templates.searchResults = new SearchResults(searchResultsEl);
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

	initSocialShare() {
		const socialShareEls = document.querySelectorAll('[data-social-share]');

		Array.from(socialShareEls).forEach((el) => {
			el.addEventListener('click', (e) => {
				const site = el.getAttribute('data-social-share');
				const shareUrl = el.getAttribute('href');

				e.preventDefault();

				if (!shareUrl) {
					return;
				}

				window.open(shareUrl, `${site}Share`, 'width=626,height=436');
			});
		});
	}
}

export default App;
