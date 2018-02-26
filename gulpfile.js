var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var runSequence = require('run-sequence');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function(){
  return gulp.src('_scss/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass()) // Converts Sass to CSS with gulp-sass
    .on('error', function (err) {
        console.log(err.toString());
        this.emit('end');
    })
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(''))
});

gulp.task('autoprefixer', function(){
    gulp.src('style.css')
        .pipe(autoprefixer({
            browsers: ['last 2 versions']
            // cascade: false
        }))
        .pipe(gulp.dest(''))
});

gulp.task('watch', function() {
	gulp.watch('_scss/**/**/**/*.scss', ['sass', 'autoprefixer']);
});

gulp.task('default', function (callback) {
	runSequence(['autoprefixer', 'sass', 'watch'],
		callback
	)
})