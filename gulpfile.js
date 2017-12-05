var gulp = require('gulp'),
plumber = require('gulp-plumber'),
rename = require('gulp-rename'),
autoprefixer = require('gulp-autoprefixer'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
imagemin = require('gulp-imagemin'),
cache = require('gulp-cache'),
minifycss = require('gulp-minify-css'),
less = require('gulp-less'),
connect = require('gulp-connect-php'),
browserSync = require('browser-sync');


gulp.task('browser-sync', function() {
  connect.server({
    base: 'public/',
  }, function (){
    browserSync({
      proxy: '127.0.0.1:8000'
    });
  });
});

gulp.task('bs-reload', function () {
  browserSync.reload();
});

gulp.task('images', function(){
  gulp.src('public/src/img/**/*')
  .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
  .pipe(gulp.dest('public/dist/img/'));
});

gulp.task('styles', function(){
  gulp.src(['public/src/less/**/*.less'])
  .pipe(plumber({
    errorHandler: function (error) {
      console.log(error.message);
      this.emit('end');
    }}))
    .pipe(less())
    .pipe(autoprefixer('last 2 versions'))
    .pipe(gulp.dest('public/dist/css/'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest('public/dist/css/'))
    .pipe(browserSync.reload({stream:true}))
  });

  gulp.task('scripts', function(){
    gulp.src('public/src/js/**/*.js')
    .pipe(plumber({
      errorHandler: function (error) {
        console.log(error.message);
        this.emit('end');
      }}))
      .pipe(concat('main.js'))
      .pipe(gulp.dest('public/dist/js/'))
      .pipe(rename({suffix: '.min'}))
      .pipe(uglify())
      .pipe(gulp.dest('public/dist/js/'))
      .pipe(browserSync.reload({stream:true}))
    });

    gulp.task('default', ['browser-sync'], function(){
      gulp.watch("public/src/less/**/*.less", ['styles']);
      gulp.watch("public/src/js/**/*.js", ['scripts']);
      gulp.watch("public/*.html", ['bs-reload']);
    });

    gulp.task('build', ['images', 'styles','scripts']);