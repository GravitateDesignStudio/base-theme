/* global jQuery */

import objectFitImages from 'object-fit-images';
import gravUtil from './util/grav-util';
import ThemeWelcome from './components/testing/theme-welcome';
import SiteHeader from './components/site-header';

const instances = {
	components: {
		themeWelcome: null,
		siteHeader: null
	}
};

jQuery(function ($) {
	/**
	 * Place items in here to have them run after the Dom is ready
	 */
	$(document).ready(function () {
		gravUtil.filterLinks();
		gravUtil.setHeightVars();
		gravUtil.setScrollVars();
		gravUtil.updateScrollClasses(100);

		/**
		 * Initialize components
		 */
		// theme welcome -- this can be removed during development
		const $themeWelcomeEls = $('.theme-welcome');

		if ($themeWelcomeEls.length) {
			instances.themeWelcome = new ThemeWelcome($themeWelcomeEls.first());
		}

		// site header
		const $siteHeaderEls = $('.site-header');

		if ($siteHeaderEls.length) {
			instances.components.siteHeader = new SiteHeader($siteHeaderEls.first());
		}

		// polyfill for browsers that don't support object-fit and object-position
		// ObjectFitImages: create the necessary 'font-family' CSS attribute
		$('img').each(function () {
			var objectFit = $(this).css('object-fit') || 'cover';
			var objectPos = $(this).css('object-position') || '50% 50%';
			var fontFamilyParams = [];

			if (objectFit) {
				fontFamilyParams.push('object-fit: ' + objectFit);
			}

			if (objectPos) {
				fontFamilyParams.push('object-position: ' + objectPos);
			}

			if (fontFamilyParams.length) {
				$(this).css('font-family', '"' + fontFamilyParams.join('; ') + ';"');
			}
		});

		// ObjectFitImages: only act upon images that have a valid src attribute
		objectFitImages('img:not([src^="data:"])');
	});

	/**
	 * Place items in here to have them run the page is loaded
	 */
	$(window).load(function () {
	});

	/**
	 * Place items in here to have them run when the window is scrolled
	 */
	$(window).scroll(function () {
		gravUtil.setScrollVars();
		gravUtil.updateScrollClasses(100);
	});

	/**
	 * Place items in here to have them run when the window is resized
	 */
	$(window).resize(function () {
	});
});
