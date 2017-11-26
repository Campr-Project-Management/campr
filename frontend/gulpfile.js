var gulp = require('gulp'),
	less = require('gulp-less'),
	watch = require('gulp-watch'),
	cleanCSS = require('gulp-clean-css');

gulp.task('less', function () {
    process.chdir('../web/assets/admin');

	return gulp
		.src('less/*.less')
		.pipe(less())
		.pipe(cleanCSS())
		.pipe(gulp.dest('../web/assets/admin/css/'))
	;
});

gulp.task('watch-css', function () {
    process.chdir('../web/assets/admin');

    gulp.watch('less/*.less', ['less']);
});

gulp.task('default', ['less']);
