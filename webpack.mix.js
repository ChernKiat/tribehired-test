const mix = require('laravel-mix');
mix.webpackConfig({
    target: "node",
    resolve: {
        // aliasFields: ["main", 'browser'],
        mainFields: ['main', 'browser'],
        // fallback: {
        //     fs: false,
        //     // http: false,
        //     http: require.resolve('stream-http'),
        //     // https: false,
        //     https: require.resolve('https-browserify'),
        //     // crypto: false,
        //     crypto: require.resolve('crypto-browserify'),
        //     stream: require.resolve('stream-browserify'),
        //     // zlib: false,
        //     // zlib: require.resolve('browserify-zlib'),
        // },
    },
});
// mix.js('resources/js/myNFTStorage/main.js', 'public/myNFTStorage/');
mix.js('resources/js/myNFTStorage/waifulabs.js', 'public/myNFTStorage/');
// mix.js('node_modules/waifusocket/waifulabs.js', 'public/myNFTStorage/');

// mix.js('resources/js/app.js', 'public/js')
//     .js('resources/js/bootstrap.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/assets/css');

mix.js('public/mySealChamber/app.js', 'public/js/ws/app.js')
    .js('public/mySealChamber/ws/client.js', 'public/js/ws/client.js');

mix.scripts([
    'public/assets/js/jquery-3.3.1.min.js',
    'public/assets/js/popper.min.js',
    'public/assets/js/bootstrap.min.js',
    'public/assets/js/moment.min.js',
    'public/assets/js/sweetalert.min.js',
    'public/assets/js/delete.handler.js',
    'public/assets/js/all.min.js',
    // 'public/assets/plugins/js-cookie/js.cookie.js',
    'public/vendor/jsvalidation/js/jsvalidation.js',
    'public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',
    'public/assets/plugins/croppie/croppie.js'
], 'public/assets/js/vendor.js');

mix.styles([
    'public/assets/css/all.min.css',
    'public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css',
    'public/assets/plugins/croppie/croppie.css',
], 'public/assets/css/vendor.css');

// mix.js('resources/js/myHololiveFan/puppeteer/mySubtitlesDownloader.js', 'public/myHololiveFan');

// mix.js('resources/js/plugin.js', 'public/js')
//     .copy('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
//         'public/css/datatables.net-bs4/css/dataTables.bootstrap4.min.css')
//     .copy('node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
//         'public/css/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css');

// mix.js('resources/js/icheck.js', 'public/js/icheck')
//     .copy('node_modules/icheck/skins/flat/blue.css', 'public/css/icheck/skins/flat/blue.css') // no minified
//     .copy('node_modules/icheck/skins/flat/blue.png', 'public/css/icheck/skins/flat/blue.png')
//     .copy('node_modules/icheck/skins/flat/blue@2x.png', 'public/css/icheck/skins/flat/blue@2x.png');

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

