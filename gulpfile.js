'use strict';

const gulp         = require('gulp');
const concat       = require('gulp-concat');
const uglify       = require('gulp-uglify');
const minifycss    = require('gulp-minify-css');

const adminPath = 'public/static/admin/';
const balticrestPath = 'public/static/balticrest/';

// ==================================================== Admin CSS ======================================================

gulp.task('admin:css', function() {
    return gulp.src([
        adminPath + 'src/bootstrap/css/bootstrap.min.css',
        adminPath + 'src/font-awesome/css/font-awesome.css',
        adminPath + 'src/light-bootstrap/css/light-bootstrap-dashboard.css'
    ])
        .pipe(minifycss())
        .pipe(concat('admin.css'))
        .pipe(gulp.dest(adminPath + 'build'));
});

// ==================================================== Admin JS =======================================================

gulp.task('admin:js', function() {
    return gulp.src([
        adminPath + 'src/jquery/js/jquery.min.js',
        adminPath + 'src/bootstrap/js/bootstrap.bundle.min.js',
        adminPath + 'src/bootstrap/js/bootstrap-datepicker.js',
        adminPath + 'src/bootstrap/js/bootstrap-notify.js',
        adminPath + 'src/bootstrap/js/bootstrap-switch.js',
        adminPath + 'src/light-bootstrap/js/light-bootstrap-dashboard.js'
    ])
        .pipe(uglify())
        .pipe(concat('admin.js'))
        .pipe(gulp.dest(adminPath + 'build'));
});

// ==================================================== Balticrest CSS ======================================================

gulp.task('balticrest:css:main', function() {
    return gulp.src([
        balticrestPath + 'src/bootstrap/css/bootstrap.css',
        balticrestPath + 'src/pushy/css/pushy.css',
        balticrestPath + 'src/animate/css/animate.css',
        balticrestPath + 'src/font-awesome/css/font-awesome.css',
        balticrestPath + 'src/balticrest/css/main.css',
        balticrestPath + 'src/balticrest/css/adaptive.css'
    ])
        .pipe(minifycss())
        .pipe(concat('balticrest_main.css'))
        .pipe(gulp.dest(balticrestPath + 'build'));
});

// ==================================================== Balticrest JS =======================================================

gulp.task('balticrest:js:main', function() {
    return gulp.src([
        balticrestPath + 'src/jquery/js/jquery.min.js',
        balticrestPath + 'src/bootstrap/js/bootstrap.min.js',
        balticrestPath + 'src/balticrest/js/all_scr.js'
    ])
        .pipe(uglify())
        .pipe(concat('balticrest_main.js'))
        .pipe(gulp.dest(balticrestPath + 'build'));
});

gulp.task('balticrest:js:map', function() {
    return gulp.src([balticrestPath + 'src/balticrest/js/map.js'])
        .pipe(uglify())
        .pipe(concat('balticrest_map.js'))
        .pipe(gulp.dest(balticrestPath + 'build'));
});

// ===================================================== Main ==========================================================

gulp.task('prod', gulp.parallel('admin:css', 'admin:js', 'balticrest:css:main', 'balticrest:js:main', 'balticrest:js:map'));

gulp.task('default', gulp.series('prod'));
