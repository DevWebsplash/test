import gulp from 'gulp';
import shell from 'gulp-shell';
import cache from 'gulp-cache';

import sass from 'gulp-dart-sass';
import autoprefixer from 'gulp-autoprefixer';
import cleancss from 'gulp-clean-css';
import concat from 'gulp-concat';
import rename from 'gulp-rename';
import terser from 'gulp-terser';
import sourcemaps from 'gulp-sourcemaps';
import connect from 'gulp-connect-php';
import browserSync from 'browser-sync';
import plumber from 'gulp-plumber';


const themePrefix = 'pct';

export function install() {
    return gulp.src('package.json', { read: false })
        .pipe(shell([
            'npm install'
        ]));
}


// Paths and files
const paths = {
    styles: {
        src: 'scss/**/*.scss',
        dest: 'css'
    },
    scripts: {
        src: [
            'js/libs/*.js',
            'js/scripts/common.js'
        ],
        dest: 'js'
    },
    html: './*.html',
    php: '**/*.php'
};



// Style task with error handling
export function css() {
    return gulp.src(paths.styles.src)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({ cascade: false }))
        .pipe(rename(`${themePrefix}.min.css`))
        .pipe(cleancss())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.stream());
}

// Script task with error handling
export function js() {
    return gulp.src(paths.scripts.src)
        .pipe(plumber())
        .pipe(concat(`${themePrefix}.min.js`))
        .pipe(terser())
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(browserSync.stream());
}

// HTML task
export function html() {
    return gulp.src(paths.html)
        .pipe(browserSync.stream());
}

// Watch task with error handling
export function watch() {
    connect.server({}, function () {
        browserSync.init({
            proxy: '127.0.0.1:8000',
            notify: false
        });
    });

    gulp.watch(paths.styles.src, css);
    gulp.watch(paths.scripts.src, js);
    gulp.watch(paths.php).on('change', browserSync.reload);
    gulp.watch(paths.html).on('change', browserSync.reload);
}

// Default task
export default gulp.series(css, js);
