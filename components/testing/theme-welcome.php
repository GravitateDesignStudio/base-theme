<div class="theme-welcome">
	<div class="row">
		<div class="columns small-12 wysiwyg">
			<p><strong>Note:</strong> If you have not yet done so, make sure to run <code>npm install</code> and <code>composer install</code> in the theme root to install the necessary dependencies</p>

			<h3>Features</h3>

			<hr>

			<h4>CSS / SCSS</h4>
			<ul>
				<li>
					<h5>BEM-enabled</h5>
					<p>This starter theme is using the BEM methodology for CSS which improves modularity and helps with common specificity issues. To learn more about BEM, visit <a href="http://getbem.com/" target="_blank" rel="nofollow noopener">getbem.com</a> and <a href="https://seesparkbox.com/foundry/bem_by_example" target="_blank" rel="nofollow noopener">BEM By Example</a>.</p>
				</li>
				<li>
					<h5>Foundation 6 Flex Grid</h5>
					<p>The Foundation 6 Flex Grid is included by default. It is installed via NPM so that the current version can be easily referenced and updated if necessary.</p>
					<p>By default, only the <code>foundation-global-styles</code>, <code>foundation-flex-grid</code>, <code>foundation-visiblity-classes</code>, and <code>foundation-flex-classes</code> modules are included to keep the overall bundle size down. If you need to include additional Foundation components you can do so in <code>master.scss</code>.</p>
					<p><a href="https://foundation.zurb.com/sites/docs/flex-grid.html" target="_blank" rel="nofollow noopener">Foundation 6 Flex Grid Documentation</a></p>
				</li>
				<li>
					<h5>object-fit-images polyfill</h5>
					<p>The <a href="https://github.com/bfred-it/object-fit-images/">object-fit-images</a> polyfill has been included and will allow you to use the <code>object-fit</code> and <code>object-position</code> CSS properties in browsers that don't have support for them such as IE 11, Edge (pre-chromium), and Safari (&lt;= 9).</p>
				</li>
			</ul>

			<hr>

			<h4>PHP</h4>
			<ul>
				<li>
					<h5>Composer</h5>
					<p>3rd party PHP packages for this theme are managed using Composer. If you do not have Composer installed you can do so by using <a href="https://brew.sh/" target="_blank" rel="nofollow noopener">Homebrew</a> <code>brew install composer</code> or by going to <a href="https://getcomposer.org/" target="_blank" rel="nofollow noopener">https://getcomposer.org/</a>.</p>
				</li>
				<li>
					<h5>WP-Util Package</h5>
					<p>The WP-Util package (repo: <a href="https://github.com/dougfrei/wp-util"  target="_blank" rel="nofollow noopener">https://github.com/dougfrei/wp-util</a> | packagist: <a href="https://packagist.org/packages/dfrei/wp-util">https://packagist.org/packages/dfrei/wp-util</a>) for Composer contains many theme-independent utility methods for WordPress and common 3rd party integrations.</p>
				</li>
			</ul>

			<hr>

			<h4>JavaScript</h4>
			<ul>
				<li>
					<h5>ESLint</h5>
					<p>ESLint is a tool that will compare the JavaScript in the theme to a defined set of rules. While it is not a part of the build process, it's highly recommended to run ESLint regularly in order to maintain consistency and prevent errors. The included <code>.eslintrc.json</code> file should be usable by linting plugins in editors such as Visual Studio Code and Atom in order to lint JS as you're editing it. To run ESLint manually, use the following command at the theme root: <code>npm run eslint</code> ESLint will hurt your feelings -- but that's a good thing.</p>
				</li>
				<li>
					<h5>Build System based on Gulp, dart-sass, and Webpack</h5>
					<p>Gulp is being used as the build system for this theme and the currently configured tasks can be found in <code>gulpfile.js</code>. Dart-sass is being used to parse the SCSS source files and it is configured within <code>gulpfile.js</code>. WebPack in combination with Babel is being used to transpile and minify the JavaScript source files.</p>
					<p>With the combination of Webpack and Babel, it is possible to use <a href="https://babeljs.io/learn-es2015/" target="_blank" rel="nofollow noopener">ES6+</a> features in your code without worrying about browser compatibility. Additionally, both the <a href="https://nodejs.org/docs/latest/api/modules.html" target="_blank" rel="nofollow noopener">CommonJS</a> and <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import" target="_blank" rel="nofollow noopener">ES6 Module</a> (<i>preferred</i>) formats can be used to modularize your code.</p>
				</li>
				<li>
					<h5>Included Libraries</h5>

					<strong>Embla Carousel</strong>
					<p><a href="https://www.embla-carousel.com/" target="_blank" rel="nofollow noopener">Embla Carousel</a> can be included via the <code>loadEmblaCarousel</code> method in <code>js/util/load-dependencies.js</code>. This method will include the library dynamically and return a promise with the Embla instance after a successful load. An example of how to use Embla Carousel is located below.</p>
				</li>
				<li>
					<h5>Modal Functionality</h5>

					<button class="button js__example-modal--default" type="button">Show Example Modal</button>
					<button class="button js__example-modal--loading" type="button">Show Loading Modal</button>
					<button class="button js__example-modal--video" type="button" data-video-url="https://www.youtube.com/watch?v=PS3jlUPPf5U">Show Video Modal</button>
				</li>
			</ul>

			<hr>
			<h3>How To Build</h3>
			<p>The following NPM script aliases have been include and can be run with <code>npm run &lt;script-name&gt;</code>:</p>
			<ul>
				<li><code>eslint</code> - Run ESLint against your JavaScript to check for any issues or errors</li>
				<li><code>build-css</code> - Run the Gulp build task for your SCSS files</li>
				<li><code>build-js</code> - Run the Gulp build task for your JavaScript files</li>
				<li><code>build</code> - Run both the CSS and JS build tasks together</li>
				<li><code>watch</code> - Start a BrowserSync proxy instance and put both build tasks into a watch state where they will trigger on any changes to the source files</li>
			</ul>
		</div>
	</div>

	<div class="block-container bg-black">
		<div class="block-inner">
			<div class="row">
				<div class="columns small-12">
					<div class="embla-instance">
						<div class="embla">
							<div class="embla__container">
								<div class="embla__slide">
									<img src="https://picsum.photos/640/480?random" alt="slide 1">
								</div>
								<div class="embla__slide">
									<img src="https://picsum.photos/640/480?random" alt="slide 2">
								</div>
								<div class="embla__slide">
									<img src="https://picsum.photos/640/480?random" alt="slide 3">
								</div>
							</div>
						</div>
						<div class="embla__controls">
							<button class="embla__nav-button embla__nav-button--prev" type="button">
								<?php WPUtil\SVG::the_svg('general/chevron-left', ['class' => 'embla__nav-arrow embla__nav-arrow--left']); ?>
							</button>
							<div class="embla__pagination"></div>
							<button class="embla__nav-button embla__nav-button--next" type="button">
								<?php WPUtil\SVG::the_svg('general/chevron-right', ['class' => 'embla__nav-arrow embla__nav-arrow--right']); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<section class="section-container">
	<div class="section-inner">
		<div class="row">
			<div class="columns small-12">
				<?php
				WPUtil\Component::render(
					'components/entry',
					[ 'post_id' => 2 ]
				);
				?>
			</div>
		</div>
	</div>
</section>
