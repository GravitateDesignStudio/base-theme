const fs = require('fs');
const path = require('path');
const { series, parallel, src, dest, watch } = require('gulp');
const plugins		= require('gulp-load-plugins')();
const browsersync	= require('browser-sync').create();
const webpack		= require('webpack');
const webpackStream	= require('webpack-stream');
const webpackConfig	= require('./webpack.config.js');

const watchPaths = {
	scss: 'css/**/*.scss',
	js: 'js/**/*.js',
	php: ['**/*.php', '!vendor/', '!node_modules/', '!media/', '!js/', '!css/', '!dist/']
};

const srcFiles = {
	scss: 'css/master.scss',
	scssEditorStyles: 'css/editor-styles.scss',
	js: 'js/master.js'
};

const distFiles = {
	scss: 'dist/css/master.min.css',
	scssEditorStyles: 'dist/css/editor-styles.min.css',
	js: 'dist/js/master.min.js'
};

function loadLocalConfig() {
	try {
		return JSON.parse(fs.readFileSync('local_config.json'));
	} catch (e) {
		return false;
	}
}

/**
 * Build Tasks
 */
function buildSCSSwithInput(srcFile, destPath = 'dist') {
	const basename = path.basename(srcFile, '.scss');

	return src(srcFile)
		.pipe(plugins.plumber({
			errorHandler: function (err) {
				plugins.notify.onError({
					title: 'SCSS Build Error',
					message: err.message
				})(err);
			}
		}))
		.pipe(plugins.sourcemaps.init())
		.pipe(plugins.sass())
		.on('error', plugins.sass.logError)
		.pipe(plugins.autoprefixer())
		.pipe(plugins.cleanCss({
			keepSpecialComments: 0
		}))
		.pipe(plugins.rename(`${basename}.min.css`))
		.pipe(plugins.sourcemaps.write('.'))
		.pipe(dest(destPath))
		.on('error', plugins.notify.onError({
			message: '<%= error.message %>',
			title: 'SCSS Error'
		}));
}

function buildSCSStheme() {
	return buildSCSSwithInput(srcFiles.scss);
}

function buildSCSSeditor() {
	return buildSCSSwithInput(srcFiles.scssEditorStyles);
}

function buildJS() {
	return src(srcFiles.js)
		.pipe(plugins.plumber({
			errorHandler: function (err) {
				plugins.notify.onError({
					title: 'JS Build Error',
					message: err.message
				})(err);
			}
		}))
		.pipe(plugins.notify({
			message: 'Starting JS build',
			title: 'JS Build'
		}))
		.pipe(webpackStream(webpackConfig, webpack))
		.pipe(dest('dist/js'));
}

/**
 * Watch Tasks
 */
function watchSourceSCSS(cb) {
	watch(watchPaths.scss, parallel(buildSCSStheme, buildSCSSeditor));
	cb();
}

function watchSourceJS(cb) {
	watch(watchPaths.js, buildJS);
	cb();
}

/**
 * BrowserSync
 */
function startBrowserSync(cb) {
	const localConfig = loadLocalConfig();

	if (!localConfig) {
		cb(new Error('Unable to load "local_config.json" -- please use "local_config_example.json" as a template'));
		return;
	}

	browsersync.init(localConfig.browserSync);

	watch([distFiles.js].concat(watchPaths.php), () => {
		browsersync.reload();
	});

	cb();
}

/**
 * Exports
 */
exports.build_scss = parallel(buildSCSStheme, buildSCSSeditor);
exports.build_scss_theme = buildSCSStheme;
exports.build_scss_editor = buildSCSSeditor;
exports.build_js = buildJS;
exports.build = parallel(buildSCSStheme, buildSCSSeditor, buildJS);

exports.watch_scss = series(parallel(buildSCSStheme, buildSCSSeditor), watchSourceSCSS);
exports.watch_js = series(buildJS, watchSourceJS);
exports.watch = series(parallel(buildSCSStheme, buildSCSSeditor, buildJS), watchSourceSCSS, watchSourceJS);

exports.browsersync = startBrowserSync;

exports.default = series(parallel(buildSCSStheme, buildSCSSeditor, buildJS), watchSourceSCSS, watchSourceJS, startBrowserSync);
