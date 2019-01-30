/* global jQuery */

// This '@babel/polyfill' module is necessary for async/await functionality
import '@babel/polyfill';

import objectFitImages from 'object-fit-images';
import ThemeWelcome from './components/testing/theme-welcome';
import SiteHeader from './components/site-header';
import { processExternalLinks } from './util/general-util';
import ScrollWatcher from './util/scroll-watcher';

const App = (function ($) {
	return class {
		constructor() {
			this.instances = {
				components: {
					themeWelcome: null,
					siteHeader: null
				},
				templates: {},
				scrollWatcher: null
			};

			this.init();
			this.initComponents();
			this.initTemplates();
		}

		init() {
			processExternalLinks({
				target: '_blank',
				rel: 'noopener'
			});

			this.scrollWatcher = new ScrollWatcher((params) => {
				ScrollWatcher.defaultCallback(params, 100);
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
		}

		initTemplates() {
			// initialize templates here
		}
	};
})(jQuery);

export default App;
