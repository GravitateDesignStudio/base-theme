function loadLocalConfig() {
	const fs = require('fs');

	try {
		return JSON.parse(fs.readFileSync('local_config.json'));
	} catch (e) {
		return false;
	}
}

const gulp			= require('gulp');
const plugins		= require('gulp-load-plugins')();
const browsersync	= require('browser-sync').create();
const webpack		= require('webpack');
const webpackStream	= require('webpack-stream');
const webpackConfig	= require('./webpack.config.js');

const watchPaths = {
	scss: 'css/**/*.scss',
	js: 'js/**/*.js',
	php: ['**/*.php', '!vendor/**', '!media/**', '!js/**', '!dist/**', '!css/**']
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

gulp.task('build-scss', function () {
	return gulp.src(srcFiles.scss)
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
		.pipe(plugins.autoprefixer({
			browsers: ['last 2 versions', 'ie >= 11']
		}))
		.pipe(plugins.cleanCss({
			keepSpecialComments: 0
		}))
		.pipe(plugins.rename('master.min.css'))
		.pipe(plugins.sourcemaps.write('.'))
		.pipe(gulp.dest('dist/css'))
		.pipe(browsersync.stream())
		.on('error', plugins.notify.onError({
			message: '<%= error.message %>',
			title: 'SCSS Error'
		}));
});

gulp.task('build-scss-editor-styles', function () {
	return gulp.src(srcFiles.scssEditorStyles)
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
		.pipe(plugins.autoprefixer({
			browsers: ['last 2 versions', 'ie >= 11']
		}))
		.pipe(plugins.cleanCss({
			keepSpecialComments: 0
		}))
		.pipe(plugins.rename('editor-styles.min.css'))
		.pipe(plugins.sourcemaps.write('.'))
		.pipe(gulp.dest('dist/css'))
		.on('error', plugins.notify.onError({
			message: '<%= error.message %>',
			title: 'SCSS (editor styles) Error'
		}));
});

gulp.task('build-js', function () {
	return gulp.src(srcFiles.js)
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
		.pipe(gulp.dest('dist/js'));
});

gulp.task('build', gulp.parallel('build-scss', 'build-scss-editor-styles', 'build-js'));

gulp.task('browser-sync', function () {
	const localConfig = loadLocalConfig();

	if (!localConfig) {
		console.error('Unable to load "local_config.json" -- please use "local_config_example.json" as a template');
		process.exit();
	}

	browsersync.init(localConfig.browserSync);
});

gulp.task('browser-sync-reload', function (done) {
	browsersync.reload();
	done();
});

gulp.task('watch-dist-files', function () {
	gulp.watch(watchPaths.php, gulp.series('browser-sync-reload'));
	gulp.watch(distFiles.js, gulp.series('browser-sync-reload'));
});

gulp.task('watch-source-scss', function () {
	gulp.watch(watchPaths.scss, gulp.parallel('build-scss', 'build-scss-editor-styles'));
});

gulp.task('watch-source-js', function () {
	gulp.watch(watchPaths.js, gulp.parallel('build-js'));
});

gulp.task('watch', gulp.series(
	gulp.parallel('build'),
	gulp.parallel('watch-source-scss', 'watch-source-js', 'watch-dist-files')
));

gulp.task('default', gulp.series(
	gulp.parallel('build'),
	gulp.parallel('browser-sync', 'watch')
));
