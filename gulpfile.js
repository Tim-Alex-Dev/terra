'use strict';

/**************** gulpfile.js configuration ****************/

const

	// directory locations
	dir = {
		nm: 'node_modules/',
		src: 'assets/',
		build: 'dist/'
	},
	config = require('./starter.config.json'), // project settings, run `gulp init` after changing them
	url = config.devUrl, // local site URL for BrowserSync
	themeTextDomain = config.textDomain, // applied with `gulp textdomain` (or `gulp init`)

	// modules
	gulp = require('gulp'),
	gulpif = require('gulp-if'),
	browserslist = require('browserslist'),
	browsersync = require('browser-sync').create(),
	lightningcss = require('lightningcss'),
	{Transform} = require('node:stream'),
	PluginError = require('plugin-error'),
	replace = require('gulp-replace'),
	fs = require('node:fs'),
	{rm} = require('node:fs/promises'),
	sass = require('gulp-sass')(require('sass')),
	sassGlob = require('gulp-sass-glob'),
	size = require('gulp-size'),
	webpackStream = require('webpack-stream');

// Working environment
let isProd = false; // dev by default

/**************** textdomain task ****************/

// The text domain, which is used in the theme files right now, is the one from style.css header
function currentTextDomain() {

	const match = fs.readFileSync('style.css', 'utf8').match(/^Text Domain:\s*(\S+)/m);

	return match ? match[1] : '_it_start';
}

function textdomain(done) {

	const from = currentTextDomain();

	if (from === themeTextDomain) {
		console.log(`Text domain is already "${themeTextDomain}", nothing to replace.`);
		return done();
	}

	return gulp.src('./**/*', {
		ignore: [
			'gulpfile.js', 'starter.config.json', 'package-lock.json', dir.nm + '**', dir.build + '**',
			'**/*.{png,jpg,jpeg,gif,webp,ico,woff,woff2}' // binary files: no text to replace
		],
		encoding: false
	})
		.pipe(replace(from, themeTextDomain))
		.pipe(gulp.dest('./'));

}

/**************** init task ****************/

// Writes a file only if its content changes (no new modification time for nothing)
function writeIfChanged(file, content) {

	if (fs.readFileSync(file, 'utf8') !== content) {
		fs.writeFileSync(file, content);
	}
}

// Applies starter.config.json: text domain, theme name (style.css), package name, deploy folder (.gitlab-ci.yml, GitLab CI add-on)
function initSettings(done) {

	writeIfChanged('style.css', fs.readFileSync('style.css', 'utf8').replace(/^Theme Name:.*$/m, `Theme Name: ${config.themeName}`));

	const pkg = JSON.parse(fs.readFileSync('package.json', 'utf8'));
	pkg.name = config.themeSlug;
	writeIfChanged('package.json', JSON.stringify(pkg, null, 2) + '\n');

	if (fs.existsSync('.gitlab-ci.yml')) {
		writeIfChanged('.gitlab-ci.yml', fs.readFileSync('.gitlab-ci.yml', 'utf8').replace(/themes\/[^/\s;]+\//g, `themes/${config.themeFolder}/`));
	}

	done();
}

/**************** fonts task ****************/

const fontsConfig = {

	src: dir.src + 'fonts/**/*',
	build: dir.build + 'fonts/',
	watch: dir.src + 'fonts/**/*',
};

function fonts() {

	return gulp.src(fontsConfig.src, {encoding: false})
		.pipe(gulp.dest(fontsConfig.build));

}

/**************** images task ****************/

const imgConfig = {

	src: dir.src + 'img/**/*',
	build: dir.build + 'img/',
	watch: dir.src + 'img/**/*'
};

function images() {

	return gulp.src(imgConfig.src, {encoding: false})
		.pipe(size({showFiles: true}))
		.pipe(gulp.dest(imgConfig.build));

}

/**************** CSS task ****************/

const cssConfig = {

	src: dir.src + 'scss/*.scss',
	watch: dir.src + 'scss/**/*',
	build: dir.build + 'css/',
	sassOpts: {
		style: 'expanded', // minified later by LightningCSS in production
		loadPaths: [
			dir.nm
		],
		quietDeps: !process.env.SASS_VERBOSE, // do not show Sass deprecation warnings from imported files (`SASS_VERBOSE=1 gulp css` shows them all)
		verbose: !!process.env.SASS_VERBOSE,
		silenceDeprecations: ['import'] // TODO: migrate @import to @use / @forward before Dart Sass 3.0 (not released yet)
	},
	// Browsers to support (see "browserslist" in package.json, also used by Babel for JS): vendor prefixes are added,
	// and modern CSS (e.g. nesting) is converted, only where these browsers need it.
	targets: lightningcss.browserslistToTargets(browserslist())

};

/**
 * LightningCSS: autoprefixer + CSS minifier in one step (minify in production only)
 */
function lightning() {

	return new Transform({
		objectMode: true,
		transform(file, encoding, callback) {
			if (file.isNull()) {
				return callback(null, file);
			}

			try {
				const result = lightningcss.transform({
					filename: file.path,
					code: file.contents,
					minify: isProd,
					targets: cssConfig.targets,
					sourceMap: !!file.sourceMap,
					inputSourceMap: file.sourceMap ? JSON.stringify(file.sourceMap) : undefined
				});

				file.contents = Buffer.from(result.code);
				if (result.map) {
					file.sourceMap = JSON.parse(result.map.toString());
				}
				callback(null, file);
			} catch (error) {
				callback(new PluginError('lightningcss', error));
			}
		}
	});
}

function css() {

	return gulp.src(cssConfig.src, {sourcemaps: !isProd}) // inline source maps in development only
		.pipe(sassGlob())
		.pipe(sass(cssConfig.sassOpts).on('error', function(error){
			const message = new PluginError('sass', error.messageFormatted).toString();
			process.stderr.write(`${message}\n`);
			this.emit('end');
			if ( isProd ) {
				throw new Error('Check your sass files');
			}
		}))
		.pipe(lightning())
		.pipe(size({showFiles: true}))
		.pipe(gulp.dest(cssConfig.build, {sourcemaps: !isProd}))
		.pipe(gulpif(!isProd, browsersync.reload({stream: true})));
}

function cleanDest() {
	return rm(dir.build, {recursive: true, force: true});
}

/**************** JS task ****************/

const jsConfig = {

	srcMain: dir.src + 'js/main.js',
	srcCopy: dir.src + 'js/copy/*.js',
	watch: dir.src + 'js/**/*',
	watchCopy: dir.src + 'js/copy/*.js',
	build: dir.build + 'js/'

};

// Module files have a content hash in their name: remove the old ones before a new build
function cleanJsModules() {
	return rm(jsConfig.build + 'modules', {recursive: true, force: true});
}

function webpack() {

	return gulp.src(jsConfig.srcMain)
		.pipe(webpackStream({
			mode: isProd ? 'production' : 'development',
			output: {
				filename: 'main.js',
				chunkFilename: 'modules/[name].[contenthash:8].js', // modules loaded on demand by main.js (see functions/load-modules.js)
				publicPath: 'auto', // module URLs are resolved from the location of main.js
			},
			module: {
				rules: [{
					test: /\.m?js$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
						options: {
							presets: ['@babel/preset-env'] // target browsers: "browserslist" in package.json (same as CSS)
						}
					}
				}]
			},
			performance: {
				hints: false
			},
			devtool: !isProd ? 'source-map' : false
		}))
		.on('error', function (err) {
			console.error('WEBPACK ERROR', err.message || err);
			if (isProd) {
				process.exitCode = 1; // fail the production build, do not deploy broken JS
			}
			this.emit('end');
		})
		.pipe(gulp.dest(jsConfig.build))
		.pipe(gulpif(!isProd, browsersync.reload({stream: true})));
}

const js = gulp.series(cleanJsModules, webpack);

function jsCopy() {

	return gulp.src(jsConfig.srcCopy)
		.pipe(gulp.dest(jsConfig.build));
}

/**************** browser-sync task ****************/

const syncConfig = {
	proxy: {
		target: url
	},
	port: 8000,
	files: [
		'./*.php',
		'./inc/**/*.php',
		'./template-parts/**/*.php'
	],
	open: false
};

function bs() {

	return browsersync.init(syncConfig);
}

/**************** watch task ****************/

function watchFiles() {
	gulp.watch(cssConfig.watch, css);
	gulp.watch(jsConfig.watch, js);
	gulp.watch(jsConfig.watchCopy, jsCopy);
	gulp.watch(imgConfig.watch, images);
	gulp.watch(fontsConfig.watch, fonts);
}

const toProd = (done) => {
	isProd = true;
	done();
};

const build = gulp.parallel(fonts, images, css, js, jsCopy);

exports.default = gulp.series(build, watchFiles); // `gulp`: dev build + watch
exports.watch = gulp.series(build, gulp.parallel(bs, watchFiles)); // `gulp watch`: + BrowserSync (devUrl)
exports.prod = gulp.series(toProd, cleanDest, build); // `gulp prod`: clean production build
exports.init = gulp.series(textdomain, initSettings);
exports.textdomain = textdomain;
exports.css = css;
exports.js = js;
exports.images = images;
exports.fonts = fonts;
