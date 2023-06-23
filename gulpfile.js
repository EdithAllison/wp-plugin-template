// Dependencies
var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));

gulp.task('sass', function(cb) {
  gulp
    .src( 'plugins/abc-def/assets/src/css/*.scss' )
    .pipe(sass())
    .pipe(gulp.dest('plugins/abc-def/assets/build/css'));
  cb();
});

gulp.task('terser', function(cb) {
  gulp
    .src('plugins/abc-def/assets/src/js/*.js')
    .pipe(terser())
    .pipe(gulp.dest('plugins/abc-def/assets/build/js'));
    cb();
});

gulp.task(
  'default',
  gulp.series('sass', 'terser', function(cb) {
    gulp.watch('plugins/abd-def/assets/src/css/*.scss', gulp.series('sass'));
    gulp.watch('plugins/abc-def/assets/src/js/*.js', gulp.series('terser'));
    cb();
  })
);