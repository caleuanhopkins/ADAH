// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var autoprefixer= require('gulp-autoprefixer');
var rename = require('gulp-rename');
var notify = require('gulp-notify');
var cache = require('gulp-cache');
var del = require('del');
var minifycss = require('gulp-clean-css');
var imagemin = require('gulp-imagemin');

var connect = require('gulp-connect');
var modRewrite = require('connect-modrewrite');
var fileinclude = require('gulp-file-include');
var livereload = require('gulp-livereload');
// Lint Task
gulp.task('lint', function() {
    return gulp.src('assets/js/src/main.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

gulp.task('sass', function() {
    return gulp.src('assets/scss/main.scss')
        .pipe(sass(
            {
                style: 'expanded',
                errLogToConsole: false,
                onError: function(err) {
                    return notify().write(err);
                }
            }
        ))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(gulp.dest('assets/css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss({ compatibility: 'ie8', noAdvanced: true }))
        .pipe(gulp.dest('assets/css'))
        .pipe(notify({ message: 'Styles task complete' }));
});

// Concatenate & Minify JS
gulp.task('libscripts', function() {
    return gulp.src([
            /*'assets/bower*/
        ])
        .pipe(jshint())
        .pipe(concat('libs.js'))
        .pipe(gulp.dest('assets/js/front/dist'))
        .pipe(uglify().on('error', function(e) { console.log('\x07',e.message); return this.end(); }))
        .pipe(concat('libs.min.js'))
        .pipe(gulp.dest('assets/js/front/dist'))
        .pipe(notify({ message: 'Library Scripts task complete' }));
});


gulp.task('libcss', function() {
    return gulp.src([
            /*'assets/bower*/
        ])
        .pipe(concat('libs.min.scss'))
        .pipe(gulp.dest('assets/scss/front'))
        .pipe(notify({ message: 'Library Styles task complete' }));
});

gulp.task('scripts', function() {
    return gulp.src(
            'assets/js/src/main.js'
        )
        .pipe(concat('all.js'))
        .pipe(gulp.dest('assets/js/front/dist'))
        .pipe(uglify().on('error', function(e) { console.log('\x07',e.message); return this.end(); }))
        .pipe(rename('all.min.js'))
        .pipe(gulp.dest('assets/js/front/dist'))
        .pipe(notify({ message: 'Scripts task complete' }));
});

gulp.task('images', function() {
  return gulp.src('assets/imgs/**')
    .pipe(cache(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true })))
    .pipe(gulp.dest('assets/imgs'))
    .pipe(notify({ message: 'Images task complete' }));
});


// Watch Files For Changes
gulp.task('watch', function() {
    //livereload.listen();
    gulp.watch('assets/js/src/**', ['scripts']);
    gulp.watch('assets/scss/main.scss', ['sass']);
    gulp.watch('assets/imgs/**', ['images']);
});

gulp.task('connect', function() {
  connect.server({
    port: 3001,
    livereload: false,
    debug: true
  });
});


// Default Task
gulp.task('default', ['images', 'libscripts', 'scripts', 'libcss', 'sass', 'connect', 'watch']);
