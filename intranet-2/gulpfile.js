/*!
 * gulp
 * $ npm install gulp-ruby-sass gulp-autoprefixer gulp-cssnano gulp-jshint gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del --save-dev
 */

// Load plugins
var gulp = require('gulp'),
        sass = require('gulp-ruby-sass'),
        autoprefixer = require('gulp-autoprefixer'),
        cssnano = require('gulp-cssnano'),
        jshint = require('gulp-jshint'),
        UglifyJS = require("uglify-es"),
        uglify = require('gulp-uglify'),
        imagemin = require('gulp-imagemin'),
        rename = require('gulp-rename'),
        concat = require('gulp-concat'),
        notify = require('gulp-notify'),
        cache = require('gulp-cache'),
        livereload = require('gulp-livereload'),
        del = require('del'),
        babel = require('gulp-babel'),
        connect = require('gulp-connect-php'),
        browserSync = require('browser-sync'),
        cleanCSS = require('gulp-clean-css');

const uglifyes = require('uglify-es');
const composer = require('gulp-uglify/composer');
uglify = composer(uglifyes, console);

function Vendor_css() {
    return gulp.src('bower_components/**/*.css')
            .pipe(cleanCSS({compatibility: 'ie8'}))
            .pipe(gulp.dest('build/css'))
            .pipe(notify({message: 'Vendor_CSS task complete'}));
}

function scripts() {
    return gulp.src(
            'scripts/**/*.js')
            .pipe(jshint.reporter('default'))
            .pipe(concat('main.js'))
            .pipe(gulp.dest('build/scripts'))
            .pipe(rename({suffix: '.min'}))
            .pipe(uglify())
            .pipe(gulp.dest('build/scripts'))
            .pipe(notify({message: 'Scripts task complete'}));
}

function Vendor_Js() {
    return gulp.src(
            'bower_components/**/*.js')
            .pipe(jshint.reporter('default'))
            .pipe(rename({suffix: '.min'}))
            .pipe(uglify())
            .pipe(gulp.dest('build/vendor'))
            .pipe(notify({message: 'VENDOR JS task complete'}));
}
gulp.task("Vendor_css", Vendor_css);
gulp.task("scripts", scripts);
gulp.task("Vendor_Js", Vendor_Js);
gulp.task('default', gulp.parallel('Vendor_css', 'scripts', 'Vendor_Js'));

// gulp.task('server', function() {
//     connect.server({}, function (){
//       browserSync({
//         proxy: '127.0.0.1:8000'
//       });
//     });
// });

// Watch
gulp.task('watch', function () {

    // Watch .scss files
    //gulp.watch('src/styles/**/*.scss', ['styles']);

    // Watch .js files
    gulp.watch('scripts/**/*.js', scripts);
    gulp.watch('bower_components/**/*.js', Vendor_Js);
    gulp.watch('bower_components/**/*.css', Vendor_css);
    // Watch image files
    //gulp.watch('src/images/**/*', ['images']);

//  // Create LiveReload server
    // livereload.listen();
//
//  // Watch any files in dist/, reload on change
    // gulp.watch(['public_html/**']).on('change', livereload.changed);

});