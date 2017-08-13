const { mix } = require('laravel-mix');

const rawAssets = {
  bower: 'resources/vendor/bower_components',
  rawjs: 'resources/assets/js',
  rawcss: 'resources/assets/css',
  rawsass: 'resources/assets/sass'
};

const publicDir = 'public';
const publicAssets = {
  fonts: publicDir + '/fonts',
  images: publicDir + '/images',
  scripts: publicDir + '/js',
  styles: publicDir + '/css'
};

mix.setPublicPath(publicDir);

// Move bower components to resources/*
mix.copy(rawAssets.bower + '/jquery/dist/jquery.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/jquery-ui/jquery-ui.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/jquery-form/dist/jquery.form.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/AdminLTE/dist/', 'public/adminlte/dist')
  .copy(rawAssets.bower + '/AdminLTE/plugins/', 'public/adminlte/plugins')
  .copyDirectory(rawAssets.bower + '/bootstrap-sass/', 'resources/assets/sass/bootstrap/')
  .copy(rawAssets.bower + '/bootstrap-sass/assets/javascripts/bootstrap.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/font-awesome/css/font-awesome.min.css', rawAssets.rawcss)
  .copy(rawAssets.bower + '/font-awesome/fonts/', publicAssets.fonts)
  .copy(rawAssets.bower + '/parsleyjs/dist/parsley.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/parsleyjs/src/parsley.css', rawAssets.rawcss)
  .copy(rawAssets.bower + '/moment/min/moment.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', rawAssets.rawjs)
  .copy(rawAssets.bower + '/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css', rawAssets.rawcss);

// scripts
const jsplugins = [
  'resources/assets/js/jquery.min.js',
  'resources/assets/js/jquery-ui.min.js',
  'resources/assets/js/jquery.form.min.js',
  'resources/assets/js/bootstrap.min.js',
  'resources/assets/js/parsley.min.js',
  'resources/assets/js/moment.min.js',
  'resources/assets/js/bootstrap-datetimepicker.min.js'
];

mix.scripts(jsplugins, publicAssets.scripts + '/plugins.min.js');
mix.scripts(rawAssets.rawjs + '/utils.js', publicAssets.scripts + '/utils.min.js');

// Finally compile Vue assets
mix.js(rawAssets.rawjs + '/app.js', publicAssets.scripts + '/app.min.js');


// Compile Sass
mix.sass(rawAssets.rawsass + '/bootstrap.scss', publicAssets.styles + '/bootstrap.min.css');
mix.sass(rawAssets.rawsass + '/app.scss', publicAssets.styles + '/app.min.css');

// Compile static css assets
const static_css = [
  rawAssets.rawcss + '/font-awesome.min.css',
  rawAssets.rawcss + '/parsley.css',
  rawAssets.rawcss + '/bootstrap-datetimepicker.min.css',
];
mix.styles(static_css, publicAssets.styles + '/plugins.min.css');
