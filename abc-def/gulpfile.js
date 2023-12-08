// Dependencies
var gulp       = require('gulp'),
	sass         = require('gulp-sass')(require('sass')),
	terser       = require('gulp-terser'),
	plumber      = require('gulp-plumber'),
	replace      = require('gulp-replace'),
	rename       = require('gulp-rename'),
	readlineSync = require('readline-sync'),
	del          = require('del'),
	glob         = require('glob'),
	ftp          = require('vinyl-ftp');

gulp.task('sass', function(cb) {
	gulp
		.src( 'assets/src/css/*.scss' )
		.pipe(sass())
		.pipe(gulp.dest('assets/build/css'));
	cb();
});

gulp.task('terser', function(cb) {
	gulp
		.src('assets/src/js/*.js')
		.pipe(plumber())
		.pipe(terser())
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('assets/build/js'));
		cb();
});

gulp.task(
	'default',
	gulp.series('sass', 'terser', function(cb) {
		gulp.watch('assets/src/css/*.scss', gulp.series('sass'));
		gulp.watch('assets/src/js/*.js', gulp.series('terser'));
		cb();
	})
);

/**
 * FTP
 */
gulp.task('deploy', function () {
	var conn = ftp.create({
		host:     'host',
		user:     'user',
		password: 'pw',
		parallel: 10
	});

	var globs = [
		'**/*',
		'!node_modules/**/*'
	];

	return gulp.src(globs, { base: '.', buffer: false })
		.pipe(conn.newer('/wp-content/plugins/mailpoet-varehusetnorge')) // only upload newer files
		.pipe(conn.dest('/wp-content/plugins/mailpoet-varehusetnorge'))
		.on('end', function() { console.log('Files uploaded successfully!'); })
		.on('data', function(file) { console.log('Uploaded: ' + file.relative); });
});



/**
 * Setup plugin
 * replace strings
 * rename files
 * delete old files
 * exclude gulpfile and /node_modules/*
 */
gulp.task('setup', function ( done ) {

	var A = readlineSync.question('Please enter the plugin folder string: ');
	var B = readlineSync.question('Please enter the short plugin string: ');
	var C = readlineSync.question('Please enter the plugin AGAL \ NAMESPACE: ');
	var D = readlineSync.question('Please enter the plugin name: ');
	var E = readlineSync.question('Please enter the plugin description: ');

	return gulp.src(['**/*.*', '!gulpfile.js', '!**/node_modules/**'])
		.pipe(replace('abc-def', A))
		.pipe(replace('ab-cd', B))
		.pipe(replace('MMM', C))
		.pipe(replace('NNN', D))
		.pipe(replace('PPP', E))
		.pipe(rename(function (path) {
			path.basename = path.basename.replace('abc-def', A);
			path.basename = path.basename.replace('ab-cd', B);
		}))
		.pipe(gulp.dest('.'))
		.on('end', function() {
			var filesToDelete = glob.sync('**/*.{php,js,css,scss}', {ignore: '**/node_modules/**'})
				.filter(file => (file.includes('abc-def') || file.includes('ab-cd')));

			del(filesToDelete);
			done();
		});
});

