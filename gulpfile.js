var gulp = require('gulp')
var mainBowerFiles = require('main-bower-files')
var concat = require('gulp-concat')
var replace = require('gulp-replace')
var sass = require('gulp-sass')
var flatten = require('gulp-flatten')
var browserSync = require('browser-sync').create()
var proxy = 'http://localhost/www/adin/CHANGE-THIS/web/app_dev.php'

gulp.task('group-js', function() {
    gulp.src(mainBowerFiles('**/*.js'))
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('web/asset'))
        .pipe(browserSync.stream())
})
gulp.task('group-css', function() {
    var urlPattern = /url(?:\(['"]?)(?:.*?)([\w\-\.\?=#&]+?)(?:['"]?\))/g

    gulp.src('bower_components/**/*.{ttf,woff,woff2,eot,svg}')
        .pipe(flatten())
        .pipe(gulp.dest('web/dist/fonts'))

    gulp.src(mainBowerFiles('**/*.css'))
        .pipe(replace(urlPattern, 'url("fonts/$1")'))
        .pipe(concat('vendor.css'))
        .pipe(gulp.dest('web/dist'))
        .pipe(browserSync.stream())
})
gulp.task('style', function() {
    gulp.src(['app/Resources/sass/*.scss'])
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('web/dist'))
        .pipe(browserSync.stream())
})
gulp.task('script', function() {
    gulp.src(['app/Resources/js/*.js'])
        .pipe(gulp.dest('web/dist'))
        .pipe(browserSync.stream())
})
gulp.task('watch', ['style','script','group-js','group-css'], function() {
    browserSync.init({
        proxy: proxy,
        online: false,
        ghostMode: false
    })

    gulp.watch('app/Resources/sass/*.scss', ['style'])
    gulp.watch('app/Resources/js/*.js', ['script'])
    gulp.watch('bower.json', ['group-js', 'group-css'])
    gulp.watch(['app/**/*', 'web/*.php', 'src/**/*']).on('change', browserSync.reload)
})
gulp.task('default', ['watch'])
