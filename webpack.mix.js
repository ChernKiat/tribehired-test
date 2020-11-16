const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.scripts([
//     'public/myMathGenius/js/base/myBaseGameHeader.js',
//     'public/myMathGenius/js/base/EventsManager.js',
//     'public/myMathGenius/js/base/myBaseGameA.js',
//     'public/myMathGenius/js/base/Scrabble.Core/Dictionary.js',
//     'public/myMathGenius/js/base/Scrabble.Core/Tile.js',
//     // 'public/myMathGenius/js/base/class/Tile.js',
//     'public/myMathGenius/js/base/Scrabble.Core/SquareType.js',
//     'public/myMathGenius/js/base/Scrabble.Core/Square.js',
//     'public/myMathGenius/js/base/Scrabble.Core/Board.js',
//     'public/myMathGenius/js/base/Scrabble.Core/Rack.js',
//     'public/myMathGenius/js/base/Scrabble.Core/Game.js',
//     'public/myMathGenius/js/base/Scrabble.UI/Html.js',
//     'public/myMathGenius/js/base/myBaseGameFooter.js'
// ], 'public/myMathGenius/js/myCombineBaseGame.js');

// mix.js('public/test/myA.js', 'public/myMathGenius/js/myNewTest.js');

mix.js('resources/js/bootstrap.js', 'public/js');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
        'public/css/datatables.net-bs4/css/dataTables.bootstrap4.min.css');

mix.js('resources/js/icheck.js', 'public/js/icheck')
   .copy('node_modules/icheck/skins/flat/blue.css', 'public/css/icheck/skins/flat/blue.css') // no minified
   .copy('node_modules/icheck/skins/flat/blue.png', 'public/css/icheck/skins/flat/blue.png')
   .copy('node_modules/icheck/skins/flat/blue@2x.png', 'public/css/icheck/skins/flat/blue@2x.png');
