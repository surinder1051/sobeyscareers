const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync');
const cheerio = require('gulp-cheerio');
const cssnano = require('cssnano');
const concat = require('gulp-concat');
const onceImporter = require('node-sass-once-importer');
const del = require('del');
const eslint = require('gulp-eslint');
const gulp = require('gulp');
const imagemin = require('gulp-imagemin');
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const sourcemaps = require('gulp-sourcemaps');
const svgmin = require('gulp-svgmin');
const svgstore = require('gulp-svgstore');
let uglify = require('gulp-uglify-es').default;

const prompt = require('gulp-prompt');
const replacens = require('gulp-replace');
const taskListing = require('gulp-task-listing');
const notifier = require('node-notifier');
const changed = require('gulp-changed');
const gutil = require('gulp-util');
const shell = require('gulp-shell');
const exec = require('child_process').exec;
const gulpif = require('gulp-if');
const debug = require('gulp-debug');
const glob = require('glob');
const filter = require('gulp-filter');
const tabify = require('gulp-tabify');
const c = require('ansi-colors');
const pxtorem = require('postcss-pxtorem');
const tap = require('gulp-tap');
const path = require('path');
const wpPot = require('gulp-wp-pot');
const fs = require('fs');

require('dotenv').config();

var config = null;
var paths = null;

var components_path = 'components/';

function getFolders(dir) {
    return fs.readdirSync(dir)
	.filter(function(file) {
		return fs.statSync(path.join(dir, file)).isDirectory();
	});
}

// Checking for existance of optional Gulpfile.json
try {
	config = require('./Gulpfile.json');
} catch (e) {
	if (e.code !== 'MODULE_NOT_FOUND') {
		gutil.log(gutil.colors.red('Syntax Error reading gulpfile.json = %o', e));
		gutil.beep();
		notifier.notify({
			title: 'Gulp Error',
			sound: true, // Only Notification Center or Windows Toasters
			message: 'Syntax Error reading gulpfile.json!'
		});
		return;
	}
	config = {
		paths: null
	};
}

// Define our default paths, used if Gulpfile.json isn't there.
paths = {
	groups: {
		sass: [{
			in: ['assets/scss/index.scss', 'assets/scss/**/*.scss'],
			out: 'dist/css/'
		},
		{
			in: ['components/**/*.scss'],
			out: 'dist/components/'
		}
		],
		js: [{
			in: ['assets/js/*.js'],
			out: 'dist/js/'
		},
		{
			in: ['components/**/*.js'],
			out: 'dist/components/'
		}
		],
		bundled_js: []
	},
	in_img: ['assets/img/*', 'assets/img/**/*', '!assets/img/*.svg'],
	in_svg: 'assets/img/svg-icons/*.svg',
	out_img: 'dist/img/',
	php: ['./*.php', './**/*.php']
};

if (config.paths) {
	paths = config.paths;
}

/**
 * Handle errors and alert the user.
 */
function handleErrors() {
	const args = Array.prototype.slice.call(arguments);

	notify
		.onError({
			title: 'Task Failed [<%= error.message %>',
			message: 'See console.',
			sound: 'Sosumi' // See: https://github.com/mikaelbr/node-notifier#all-notification-options-with-their-defaults
		})
		.apply(this, args);

	gutil.beep(); // Beep 'sosumi' again.

	// Prevent the 'watch' task from stopping.
	this.emit('end');
}

/**
 * Delete style.css and style.min.css before we minify and optimize
 */
gulp.task('clean:css', function () {
	gutil.log('clean:css');

	del(['.linting_reports/*.json']);

	return new Promise(function (resolve, reject) {
		paths.groups.sass.forEach(function (group, key) {
			del([
				group.out + '*.css',
				group.out + '**/*.css',
				group.out + '*.min.css',
				group.out + '**/*.min.css',
				group.out + '**/*.min.css.map'
			]);
			if (Object.keys(paths.groups.sass).length == key + 1) {
				resolve();
			}
		});
	});
});

/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/gulp-sourcemaps
 */
function process_scss(inputstream, group) {
	// console.log('process_scss = %o', group.in);

	const plugins = [
		autoprefixer(), // Configured via .browserlistrc
		pxtorem({
			// This allows for usage of px values which will automatically converted to rem values
			replace: false,
			propList: ['*']
		}),
		cssnano({
			zindex: false,
			reduceIdents: false,
			preset: [
				'default',
				{
					normalizeWhitespace: false
				}
			]
		})
	];

	return (
		inputstream
			// .pipe(group.in)
			.pipe(debug({ title: '#1 css' }))
			.pipe(
				changed(group.out, {
					hasChanged: changed.compareContents
				})
			)
			.pipe(debug({ title: '#2 css' }))
			.pipe(
				sassLint({
					configFile: '.scss-lint.yml',
					options: {}
				})
			)
			.pipe(sassLint.format())
			.pipe(
				tap(function (file) {
					var report_output = path.basename(file.path) + '.json';

					return gulp
						.src(file.path)
						.pipe(
							sassLint({
								configFile: '.scss-lint.yml',
								options: {
									formatter: 'JSON',
									'output-file': '.linting_reports/' + report_output
								}
							})
						)
						.pipe(sassLint.format());
				})
			)
			// Deal with errors.
			.pipe(
				plumber({
					errorHandler: handleErrors
				})
			)

			// // Wrap tasks in a sourcemap.
			.pipe(sourcemaps.init())
			.pipe(sourcemaps.identityMap())
			.pipe(debug({ title: '#3 css' }))

			// // Compile Sass using LibSass.
			.pipe(
				sass({
					// importer: require('npm-sass').importer,
					debugInfo: true,
					errLogToConsole: true,
					outputStyle: 'expanded', // Options: nested, expanded, compact, compressed
					includePaths: group.includePaths
				}).on('error', sass.logError)
			)
			.pipe(debug({ title: '#4 css' }))

			// // Parse with PostCSS plugins.
			.pipe(postcss(plugins))
			.pipe(
				rename({
					extname: '.min.css'
				})
			)
			.pipe(
				sourcemaps.write('.', {
					sourceRoot: '../../scss',
					outputStyle: 'compressed'
				})
			)

			// // Create *.min.css.
			.pipe(
				changed(group.out, {
					hasChanged: changed.compareContents
				})
			)
			.pipe(debug({ title: '#5 css' }))
			.pipe(gulp.dest(group.out))
	);
}

function process_scss_bundled(inputstream, group) {
	const plugins = [
		// autoprefixer(), // Configured via .browserlistrc
		cssnano({
			zindex: false,
			reduceIdents: false
		})
	];

	return (
		inputstream

			// Deal with errors.
			.pipe(
				plumber({
					errorHandler: handleErrors
				})
			)

			// Wrap tasks in a sourcemap.
			.pipe(sourcemaps.init())
			.pipe(sourcemaps.identityMap())

			// Join all the SCSS.
			.pipe(concat('bundled.scss'))

			// Save the SCSS before compiling.
			.pipe(gulp.dest(group.out))

			// // Compile Sass using LibSass.
			.pipe(
				sass({
					// importer: onceImporter(), // This ensures all resolved @import statements are actually imported only once.
                    importer: require('npm-sass').importer,
					debugInfo: true,
					errLogToConsole: true,
					outputStyle: 'expanded', // Options: nested, expanded, compact, compressed
					includePaths: group.includePaths
				})
			)

			// Save the CSS before minifying.
			.pipe(gulp.dest(group.out))

			// Parse with PostCSS plugins.
			.pipe(postcss(plugins))
			.pipe(
				rename({
					extname: '.min.css'
				})
			)
			.pipe(
				sourcemaps.write('.', {
					sourceRoot: '../../scss'
				})
			)

			// Create *.min.css.
			.pipe(
				changed(group.out, {
					hasChanged: changed.compareContents
				})
			)
			.pipe(gulp.dest(group.out))
			.pipe(browserSync.stream())
	);
}

function process_css_promise() {
	return new Promise(function (resolve, reject) {
		paths.groups.sass.forEach(function (group, key) {
			//  gutil.log('READING SASS IN: ' + key);
			// gutil.log('READING SASS IN: ' + group.in);
			// gutil.log('WRITING SASS OUT: ' + group.out);
			var stream = process_scss(gulp.src(group.in), group);
			if (Object.keys(paths.groups.sass).length == key + 1) {
				stream.on('end', resolve);
			}
		});
	});
}

gulp.task('combine:js', function () {
	// gutil.log('js');
	return new Promise(function (resolve, reject) {
		var folders = getFolders(components_path);
		folders.map(function(folder) {
			var js_theme = components_path + folder + '/' +  folder + '_theme.js';
			try {
				fs.accessSync(js_theme)
				var bundle_js_stream = gulp
					.src(path.join(components_path, folder, '/*.js'))
					// concat into foldername.js
					.pipe(concat(folder + '_concat.js'))
					// write to output
					.pipe(gulp.dest('dist/' + components_path + folder + '/' ))
					// minify
					.pipe(uglify())
					// rename to folder.min.js
					.pipe(rename(folder + '_concat.min.js'))
					// write to output again
					.pipe(gulp.dest('dist/' + components_path + folder + '/'));
					bundle_js_stream.on('end', resolve);
			} catch(e){
				// the file doesn't exist
			}
		 });
		 resolve();
		 clean_combinejs();
	});
} );

/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/gulp-sourcemaps
 */
gulp.task('css', function () {
	return process_css_promise();
});

/**
 * Bundle SASS task.
 */
gulp.task('bundlescss', function () {
	return paths.groups.bundled_sass.forEach(function (group) {
		return process_scss_bundled(gulp.src(group.in), group);
	});
});

/**
 * Bundle JS task.
 */
gulp.task('bundlejs', async function () {
	gutil.log('bundlejs');
	try {
		return paths.groups.bundled_js.forEach(function (group) {
			gulp
				.src(group.in)
				.pipe(debug( group.in.dirname ) )
				.pipe(
					gulpif(
						typeof group.sourcemap === 'undefined' || group.sourcemap === true,
						sourcemaps.init()
					))
				// Join all the JS.
				.pipe(concat(group.concat))
				.pipe(gulp.dest(group.out))

				.pipe(
					gulpif(
						typeof group.minify === 'undefined' || group.minify === true,
						uglify().on('error', gutil.log)
					)
				)

				// .pipe(sourcemaps.write('.',{
				// 	sourceRoot: '../../js'
				// }))

				.pipe(
					rename({
						suffix: '.min'
					})
				)
				.pipe(gulp.dest(group.out));
			// .pipe( browserSync.stream() );
		});
	} catch {
		gutil.log('bundlejs');
		return;
	}
});

/**
 * Delete Javascripts /dist before we minify and optimize
 */
gulp.task('clean:js', function () {
	gutil.log('clean:js');
	return new Promise(function (resolve, reject) {
		paths.groups.js.forEach(function (group, key) {
			del([
				group.out + '*.js',
				group.out + '**/*.js',
				group.out + '*.min.js',
				group.out + '**/*.min.js'
			]);
			if (Object.keys(paths.groups.js).length == key + 1) {
				resolve();
			}
		});
	});
});

function js(arg1, only_process_this_file) {
	// gutil.log('js');
	return new Promise(function (resolve, reject) {
		paths.groups.js.forEach(function (group, key) {
			//  gutil.log('READING JS IN: ' + key);
			// gutil.log('READING JS IN: ' + group.in);
			// gutil.log('WRITING JS OUT: ' + group.out);

			if (group.enabled === false) {
				return;
			}

			if (!only_process_this_file) {
				only_process_this_file = '**';
			}

			var f = filter([only_process_this_file], {
				restore: true
			});
			// var f2 = filter([only_process_this_file,"!node_modules"], {restore: true});

			var stream = gulp
				.src(group.in)
				// .pipe(debug({ title: '#1' }))
				.pipe(plumber())

				.pipe(f)
				// .pipe(debug({ title: '#2' }))
				.pipe(gulp.dest(group.out))
				// .pipe(f2)
				// .pipe() // notice the error event here
				.pipe(
					gulpif(
						typeof group.minify === 'undefined' || group.minify === true,
						uglify().on('error', gutil.log)
					)
				)

				// .pipe(f2.restore)
				.pipe(
					rename({
						suffix: '.min'
					})
				)
				.pipe(gulp.dest(group.out));
				gutil.log(group.out);
			// .pipe( browserSync.stream() );

			if (Object.keys(paths.groups.js).length == key + 1) {
				stream.on('end', resolve );
			}
		});
	});
}
gulp.task('main:js', function(arg1, only_process_this_file) {
	// gutil.log('js');
	return new Promise(function (resolve, reject) {
		paths.groups.js.forEach(function (group, key) {
			//  gutil.log('READING JS IN: ' + key);
			// gutil.log('READING JS IN: ' + group.in);
			// gutil.log('WRITING JS OUT: ' + group.out);

			if (group.enabled === false) {
				return;
			}

			if (!only_process_this_file) {
				only_process_this_file = '**';
			}

			var f = filter([only_process_this_file], {
				restore: true
			});
			// var f2 = filter([only_process_this_file,"!node_modules"], {restore: true});

			var stream = gulp
				.src(group.in)
				// .pipe(debug({ title: '#1' }))
				.pipe(plumber())

				.pipe(f)
				// .pipe(debug({ title: '#2' }))
				.pipe(gulp.dest(group.out))
				// .pipe(f2)
				// .pipe() // notice the error event here
				.pipe(
					gulpif(
						typeof group.minify === 'undefined' || group.minify === true,
						uglify().on('error', gutil.log)
					)
				)

				// .pipe(f2.restore)
				.pipe(
					rename({
						suffix: '.min'
					})
				)
				.pipe(gulp.dest(group.out));
			// .pipe( browserSync.stream() );

			if (Object.keys(paths.groups.js).length == key + 1) {
				stream.on('end', resolve);
			}
		});
	});
} );

/**
 * Delete clean concat files
 */
 gulp.task('clean:combinejs', function() {
	gutil.log('clean dist concat files');
	del([ 'dist/components/*_concat.min.js', 'dist/components/*_concat.min.min.js' ] );
	return new Promise(function(resolve, reject) {
		resolve();
	});
});

/**
 * JavaScript linting.
 *
 * https://www.npmjs.com/package/gulp-eslint
 */

function lint_js_src(src) {
	// Filter out vendor folders
	const f = filter(['**', '!**/vendor/*'], {
		restore: true,
		passthrough: false
	});

	src
		// .pipe(debug())
		.pipe(f)
		.pipe(
			eslint({
				configFile: 'eslintrc.js',
				fix: true
			})
		)
		.pipe(eslint.format())
		.pipe(tabify(4, true))
		.pipe(
			gulpif(
				isFixed,
				gulp.dest(function (file) {
					return file.base;
				})
			)
		);
	// .pipe(eslint.failAfterError());
}

gulp.task('js:lint', function () {
	// gutil.log('js:lint');
	return new Promise(function (resolve, reject) {
		paths.groups.js.forEach(function (group, key) {
			lint_js_src(gulp.src(group.in));
			if (Object.keys(paths.groups.js).length == key + 1) {
				resolve();
			}
		});
	});
});

/**
 * Delete the svg-icons.svg before we minify, concat.
 */
gulp.task('clean:icons', function () {
	del([paths.out_img + 'svg-icons.svg']);
});

/**
 * Minify, concatenate, and clean SVG icons.
 *
 * https://www.npmjs.com/package/gulp-svgmin
 * https://www.npmjs.com/package/gulp-svgstore
 * https://www.npmjs.com/package/gulp-cheerio
 */

// turning off until directories are fixed, figure out single svg file

// gulp.task('svg', ['clean:icons'], function () {
// 	gulp.src(paths.in_svg)
//
// 		// Deal with errors.
// 		.pipe(plumber({ 'errorHandler': handleErrors }))
//
// 		// Minify SVGs.
// 		.pipe(svgmin())
//
// 		// Add a prefix to SVG IDs.
// 		.pipe(rename({ 'prefix': 'icon-' }))
//
// 		// Combine all SVGs into a single <symbol>
// 		.pipe(svgstore({ 'inlineSvg': true }))
//
// 		// Clean up the <symbol> by removing the following cruft...
// 		.pipe(cheerio({
// 			'run': function ($, file) {
// 				$('svg').attr('style', 'display:none');
// 				$('[fill]').removeAttr('fill');
// 				$('path').removeAttr('class');
// 			},
// 			'parserOptions': { 'xmlMode': true }
// 		}))
//
// 		// Save svg-icons.svg.
// 		.pipe(gulp.dest(paths.out_svg));
// 	// .pipe( browserSync.stream() );
// });

function isFixed(file) {
	return file.eslint != null && file.eslint.fixed;
}

function log_info(msg) {
	gutil.log(gutil.colors.red(msg));
}

function log_info(msg) {
	gutil.log(gutil.colors.red(msg));
}

/**
 * Optimize images.
 *
 * https://www.npmjs.com/package/gulp-imagemin
 */
gulp.task('imagemin', function () {
	gutil.log('imagemin');
	gulp
		.src(paths.in_img)
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)
		.pipe(
			imagemin({
				optimizationLevel: 5,
				progressive: true,
				interlaced: true
			})
		)
		.pipe(gulp.dest(paths.out_img));
});

/**
 * Process tasks and reload browsers on file changes.
 *
 * https://www.npmjs.com/package/browser-sync
 */
gulp.task('watch', function () {
	// Kick off BrowserSync.

	console.log('watch');

	if (!process.env.LOCAL_PROTOCOL) {
		console.log(
			c.red(
				'BrowserSync will NOT run, missing .env configuration ( LOCAL_PROTOCOL=http:// )'
			)
		);
	}

	if (!process.env.LOCAL_PORT) {
		console.log(
			c.red(
				'BrowserSync will NOT run, missing .env configuration ( LOCAL_PORT=80 )'
			)
		);
	}

	if (
		!process.env.LOCAL_DOMAIN ||
		process.env.LOCAL_DOMAIN == 'site.test' ||
		process.env.LOCAL_DOMAIN == 'http://site.test'
	) {
		console.log(
			c.red(
				'BrowserSync will NOT run, missing .env configuration ( LOCAL_DOMAIN=local_site_domain.test )'
			)
		);
	}

	if (
		process.env.LOCAL_DOMAIN &&
		process.env.LOCAL_PORT &&
		process.env.LOCAL_PROTOCOL
	) {
		console.log(c.cyan('Watch: Setup BrowserSync'));

		var browsersync_config = {
			injectChanges: true, // Auto inject changes instead of full reload.
			watchOptions: {
				debounceDelay: 1000 // Wait 1 second before injecting.
			},
			proxy: process.env.LOCAL_PROTOCOL + process.env.LOCAL_DOMAIN,
			host: process.env.LOCAL_DOMAIN,
			https: {
				key: process.env.LOCAL_SERVER_KEY,
				cert: process.env.LOCAL_SERVER_CRT
			},
			open: 'external'
		};

		if (process.env.LOCAL_PROTOCOL == 'https://') {
			browsersync_config.https = true;
		}

		console.log('browsersync_config = %o', browsersync_config);
		browserSync(browsersync_config);
	} else {
		console.log(c.red('BrowserSync will NOT run, missing .env configuration'));
	}

	var all_css_group_paths = [];
	// var update_all_css_group_paths = [];
	// var single_update_css_group_paths = [];

	paths.groups.sass.forEach(function (group) {
		for (var i = 0; i < group.in.length; i++) {
			all_css_group_paths.push(group.in[i]);
			// 		if (group.in[i].indexOf('component') > -1) {
			// 			single_update_css_group_paths.push(group.in[i]);
			// 		}
			// 		if (group.in[i].indexOf('includes') > -1) {
			// 			update_all_css_group_paths.push(group.in[i]);
			// 		}
		}
	});

	// console.log('single_update_css_group_paths = %o', single_update_css_group_paths);

	// console.log('update_all_css_group_paths = %o', update_all_css_group_paths);

	// console.log('all_css_group_paths = %o', all_css_group_paths);

	var all_js_group_paths = [];
	paths.groups.js.forEach(function (group) {
		for (var i = 0; i < group.in.length; i++) {
			all_js_group_paths.push(group.in[i]);
		}
	});

	console.log('all_css_group_paths = %o', all_css_group_paths);

	const watcher_css = gulp.watch(all_css_group_paths, {
		debounceDelay: 2000
	});

	watcher_css.on('change', function (path, stats) {
		// if (path.indexOf('/_') > -1) {
		// 	console.log(
		// 		c.yellow('Underscore scss file change, rebuilding all files')
		// 	);
		// 	return process_css_promise();
		// }

		// if (path.indexOf('component') === -1) {
		// 	console.log(
		// 		c.yellow('Non component scss file change, rebuilding all files')
		// 	);
		// 	return process_css_promise();
		// }

		if (path.indexOf('includes') !== -1) {
			console.log(
				c.yellow('Non component scss file change, rebuilding all files')
			);
			return process_css_promise();
		}

		console.log(c.green('Component Change: Rebuilding ONLY Component Files'));

		// Split out component name
		var find_input_file_split = path.split('/');

		paths.groups.sass.forEach(group => {
			group.in.forEach(path_in => {
				glob(path_in, {}, function (er, files) {
					if (files.includes(path)) {
						console.log('path = %o', path);

						// If wildcard is used we need to add the parent folder back in so dist files go into proper folder.
						var out = group.out;
						if (path_in.indexOf('**') > -1) {
							// console.log('group.out = %o', group.out);
							// console.log('find_input_file_split = %o', find_input_file_split);

							if (find_input_file_split[1] !== 'scss') {
								// Don't apply this to root scss files, those should go into dist/css
								out = group.out + find_input_file_split[1];
							}
							if (
								typeof find_input_file_split[2] !== 'undefined' &&
								find_input_file_split[2].indexOf('.scss') === -1
							) {
								out += '/' + find_input_file_split[2];
							}
						}

						var lastChar = out.substr(-1); // Selects the last character
						if (lastChar != '/') {
							// If the last character is not a slash
							out = out + '/'; // Append a slash to it.
						}

						console.log('out = %o', out);

						var stream = process_scss(gulp.src(path), {
							in: null,
							out: out,
							includePaths: group.includePaths
						});
						stream.pipe(browserSync.stream());
					}
				});
			});
		});
	});

	const watcher_js = gulp.watch(all_js_group_paths, {
		debounceDelay: 2000
	});

	watcher_js.on('change', function (path, stats) {
		lint_js_src(gulp.src(path));
		js(null, path);
	});

	// gulp.watch(paths.in_img, gulp.series('imagemin'));
	// gulp.watch(paths.in_svg, gulp.series('icons'));
	// return gulp.watch(paths.php, gulp.series('markup'));
});

/**
 * gulp component
 *
 * Task for creating a new component skeleton
 */
gulp.task('new-component', function () {
	return gulp.src('package.json').pipe(
		prompt.prompt({
			type: 'input',
			name: 'slug',
			message: 'Enter new component slug'
		},
			function (res) {
				gulp
					.src('fp-foundation/templates/hiroy/**/*')
					.pipe(
						rename(function (path) {
							path.basename = path.basename.replace('hiroy', res.slug);
							return path;
						})
					)
					.pipe(replacens('hiroy', res.slug))
					.pipe(gulp.dest('components/' + res.slug));
			}
		)
	);
});

gulp.task('fp-foundation-version-check', function (cb) {
	exec('ping google.com', function (err, stdout, stderr) {
		console.log(stdout);
		console.log(stderr);
		cb(err);
	});
});

/**
 * Create individual tasks.
 */
gulp.task('help', taskListing); // gulp.task('icons', ['svg']);
gulp.task(
	'bundle',
	gulp.parallel('bundlescss', gulp.series('js:lint', 'bundlejs'))
);

gulp.task('pot', function () {
	return gulp.src(['**/**/*.php', '../../plugins/**/*.php'])
		.pipe(wpPot({
			domain: 'FP_TD', //force off to scan all domains
			package: 'FP'
		}))
		.pipe(gulp.dest('lang/my-theme.pot'));
});

gulp.task('pot-facetwp', function () {
	return gulp.src(['../../plugins/facetwp/**/*.php'])
		.pipe(wpPot({
			package: 'FP'
		}))
		.pipe(gulp.dest('lang/facetwp.pot'));
});


gulp.task(
	'default',
	gulp.series(
		// 'fp-foundation-version-check',
		gulp.parallel(
			// gulp.series('wp-pot'),
			gulp.series('clean:css', 'css'),
			gulp.series('clean:js', 'js:lint', js, 'bundlejs', 'combine:js')
		),
		'clean:combinejs',
		'watch'
	)
);

gulp.task(
	'build',
	gulp.series(
		gulp.parallel(
			gulp.series('clean:css', 'css'),
			gulp.series('clean:js', 'js:lint', js, 'bundlejs', 'combine:js')
		),
		'clean:combinejs',
	)
);

// If You get     gulpInst.start.apply(gulpInst, toRun);
// npm i -g gulp-cli

// TODO: Keep list of all files to delete, after run through all changed files
