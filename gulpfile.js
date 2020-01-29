const gulp      = require('gulp');

const jshint    = require('gulp-jshint');
const sass      = require('gulp-sass');
const concat    = require('gulp-concat');

const terser = require('gulp-terser');
const rename    = require('gulp-rename');
const fileInclude = require('gulp-file-include');
const bs        = require('browser-sync').create()


/**
 * This keeps browser from caching fonts for your testing environment
 */
function cacheControl (req, res, next) {
  res.setHeader('Cache-control', 'no-store')
  next()
}

// LINT JS
gulp.task('lint', function() {
    return gulp.src('assets/js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

gulp.task('sass', function() {
    return gulp.src('build-scss/*.scss')
        .pipe(sass()
            .on('error', sass.logError)
        )
        .pipe(gulp.dest('dist/css'))
        // Reload BS
        .pipe(bs.reload({stream: true}));
});

// Compile html and move to dist
gulp.task('compile-html', function() {
  return gulp.src(['components/pages/*.html', './components/templates/*.html'])
    .pipe(fileInclude({
      prefix: '@@',
      basepath: '@file'
    }))
    .pipe(gulp.dest('./dist/'))
    .pipe(bs.reload({stream: true}));
});

gulp.task('scripts', function() {
    return gulp.src('assets/js/*.js')
        .pipe(concat('all.js'))
        .pipe(gulp.dest('dist'))
        .pipe(rename('all.min.js'))
        .pipe(terser())
        .pipe(gulp.dest('dist/js'));
});


// Copy assets to dist
gulp.task('copy', function() {
    return gulp.src('./assets/**/*')
    .pipe(gulp.dest('./dist'));
});

// Copy favicons to root
gulp.task('favicons', function() {
    return gulp.src('./assets/img/favicons/*')
    .pipe(gulp.dest('./dist'));
});

// Watchin it
gulp.task('watch', function() {
    gulp.watch('assets/js/*.js', gulp.series('lint', 'scripts'));
    gulp.watch('atomic-scss/**/*.scss', gulp.parallel('sass'));
    gulp.watch('components/**/*.html', gulp.series('compile-html'));
});

// Syncin dat browser
gulp.task('browser-sync', function() {
    bs.init({
        // I don't proxy shit (in this project)
        // proxy: 'localhost:9966', // makes a proxy for localhost:8080
        server: {
            baseDir: "./dist/"
        },

        // Fancy QR for mobile testing
        plugins: ['bs-console-qrcode']
    });
});

gulp.task('default', gulp.parallel('browser-sync','lint', 'sass', 'scripts', 'compile-html', 'copy', 'watch'));