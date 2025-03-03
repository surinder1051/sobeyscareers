var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');
var env = require('gulp-env');
var c = require('ansi-colors');
var fs = require('fs');

var execSync = require('child_process').execSync;
var active_theme_name = execSync('wp theme list --format=json --status=active').toString();
active_theme_name = JSON.parse(active_theme_name);
console.log('active_theme_name = %o', active_theme_name);
active_theme_name = active_theme_name[0].name;
console.log('active_theme_name = %o', active_theme_name);

var theme_path = "../../themes/" + active_theme_name + "/";
var theme_env_file = theme_path + ".env.json";
var dist_folder = theme_path + 'dist/';

fs.access(theme_env_file, (err) => {
	if (err) {
		// file/path is not visible to the calling process
		console.log(theme_env_file + " is missing");
		console.log(err.code);
	} else {
		env(theme_env_file);
	}
});

// Development Tasks 
// -----------------

// Start browserSync server
gulp.task('browserSync', function () {
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

		// console.log('browsersync_config = %o', browsersync_config);
		browserSync(browsersync_config);
	} else {
		console.log(c.red('BrowserSync will NOT run, missing .env configuration'));
	}
})

gulp.task('sass', function () {
	return gulp.src(['**/*.scss', '!node_modules/**/*']) // Gets all files ending with .scss in app/scss and children dirs
		.pipe(sass({
			includePaths: [theme_path + "assets/scss/", theme_path + "assets/scss/includes/", theme_path + "assets/scss/includes-foundation", theme_path + "node_modules/bootstrap/scss/"]
		}).on('error', sass.logError)) // Passes it through a gulp-sass, log errors to console
		.pipe(gulp.dest(dist_folder + 'plugin/fp-slider')) // Outputs it in the css folder
		.pipe(browserSync.reload({ // Reloading with Browser Sync
			stream: true
		}));
})

// Watchers
gulp.task('watch', function () {
	console.log("watch 123");
	return gulp.watch('**/*.scss', gulp.series('sass'));
})


// gulp.task('default', gulp.series('sass', 'browserSync', 'watch'));

gulp.task('default',
	gulp.series(
		gulp.parallel(
			gulp.series('sass', 'browserSync'),
			gulp.series('watch')
		),

	));
