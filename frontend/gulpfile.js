var gulp = require('gulp'),
    minifyCSS = require('gulp-csso'),
    less = require('gulp-less');


gulp.task('admin:less', function () {
    process.chdir('../web/assets/admin');

    return gulp.src('less/*.less')
        .pipe(less())
        .pipe(minifyCSS())
        .pipe(gulp.dest('css'));
});

gulp.task('default', ['admin:less']);
