var elixir = require('laravel-elixir');

var gulp=require('gulp'),
minifyCSS=require('gulp-minify-css'),
uglify = require('gulp-uglify');

elixir.extend("compress", function(from, to) {
    gulp.task('compress', function() {
      gulp.src(from)
        .pipe(uglify())
        .pipe(gulp.dest(to));
    });
    
    return this.queueTask("compress");
});

elixir.extend("minifycss", function(from, to) {
    gulp.task('minify-css', function(){
      return gulp.src(from)
        .pipe(minifyCSS({keepBreaks:true}))
        .pipe(gulp.dest(to));
    });
    
    return this.queueTask("minify-css");
});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less([
        'app.less',
        'base.less',
        'mixin.less'
    ]);
});

elixir(function(mix) {
    mix.styles([
        "app.css"
    ], 'public/css/app.css', 'public/css');
});
