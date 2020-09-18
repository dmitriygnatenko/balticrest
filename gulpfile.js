'use strict';

const gulp         = require('gulp');
const concat       = require('gulp-concat');
const uglify       = require('gulp-uglify');
const minifycss    = require('gulp-minify-css');

const adminPath = 'public/static/admin/';
const appPath = 'public/static/balticrest/';

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


// ===================================================== Main ==========================================================

gulp.task('prod', gulp.parallel('admin:css', 'admin:js'));

gulp.task('default', gulp.series('prod'));
