var gulp        = require('gulp');
var browserSync = require('browser-sync').create();

gulp.task('serve', function() {
    
    browserSync.init({
        proxy: "localhost/wordpress",
        files: ["**/*.css", "**/*.js", "**/*.php"]
    });

});

gulp.task('default', ['serve']);