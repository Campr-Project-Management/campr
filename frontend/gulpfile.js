var gulp = require('gulp'),
	less = require('gulp-less'),
	watch = require('gulp-watch'),
	cleanCSS = require('gulp-clean-css');

gulp.task('less', function () {
	return gulp.src('../web/assets/admin/less/*.less')
		.pipe(less())
		.pipe(cleanCSS())
		.pipe(gulp.dest('../web/assets/admin/css/'));
});

gulp.task('watch-css', function (){
    gulp.watch('../web/assets/admin/less/*.less', ['less']);
});

gulp.task('default', ['less']);
