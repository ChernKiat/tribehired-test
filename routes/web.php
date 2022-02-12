<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test')->name('test');

Route::get('/ws', 'HomeController@ws')->name('ws');
Route::get('/scrabble', 'HomeController@scrabble')->name('scrabble');
Route::get('/math-genius', 'HomeController@mathGenius')->name('math.genius');
Route::get('/gmtk', 'HomeController@gmtk')->name('gmtk');
Route::get('/mash_up', 'HomeController@mashUp')->name('mash.up');

Route::prefix('board-game')->namespace('BoardGame')->name('boardgame.')->group(function() {
    Route::get('combination', 'BoardGameController@test')->name('combination.test');
});

Route::prefix('crypto-bot')->namespace('CryptoBot')->name('cryptobot.')->group(function() {
    Route::prefix('ccxt')->name('ccxt.')->group(function() {
        Route::get('test', 'CCXTController@test')->name('test');
    });
});

Route::prefix('hololive-fan')->namespace('HololiveFan')->name('hololivefan.')->group(function() {
    Route::prefix('subtitles')->name('subtitle.')->group(function() {
        Route::get('test', 'SubtitleController@test')->name('test');
    });
});

Route::prefix('net-junkies')->namespace('NetJunkies')->name('netjunkies.')->group(function() {
    Route::prefix('posts')->name('post.')->group(function() {
        Route::get('create', 'PostController@create')->name('create');
        Route::post('/', 'PostController@store')->name('store');
        Route::get('/', 'PostController@index')->name('index');
        Route::get('{id}/edit', 'PostController@edit')->name('edit');
        Route::get('{id}', 'PostController@show')->name('show');
    });
});

Route::prefix('nft-storage')->namespace('NFTStorage')->name('nftstorage.')->group(function() {
    Route::get('/', 'DemoController@main')->name('demo.main');
    Route::get('one', 'DemoController@one')->name('demo.one');
    Route::get('two', 'DemoController@two')->name('demo.two');
    Route::get('three', 'DemoController@three')->name('demo.three');
    Route::get('four', 'DemoController@four')->name('demo.four');
    Route::get('four-path', 'DemoController@path')->name('demo.path');
    Route::get('four-code', 'DemoController@code')->name('demo.code');

    Route::get('alpha', 'ImageController@alpha')->name('image.alpha');
    Route::get('alpha-response-giga', 'ImageController@response')->name('image.response');

    Route::get('meta', 'BlockchainController@meta')->name('blockchain.meta');

    Route::get('wallet', 'WalletController@main')->name('wallet.main');
});

Route::prefix('research-development')->namespace('ResearchDevelopment')->name('researchdevelopment.')->group(function() {
    Route::get('test', 'TestController@test')->name('test');
});

Route::prefix('seal-chamber')->namespace('SealChamber')->name('sealchamber.')->group(function() {
    Route::get('code', 'PageController@roomCode')->name('room.code');

    Route::prefix('rooms')->name('room.')->group(function() {
        Route::get('join', 'RoomController@join')->name('join');
    });
});

Route::namespace('Tesseract')->group(function() {
    Route::resource('documents', 'DocumentController')
        ->except('show');

    Route::prefix('documents')->name('document.')->group(function() {
        Route::get('{document}/crop', 'DocumentController@crop')->name('crop');
        Route::get('{document}/export', 'DocumentController@export')->name('export');
    });

    Route::prefix('spell-check')->name('spell.')->group(function() {
        Route::get('/', 'SpellCheckController@index')->name('check');
    });
});

Route::prefix('vengeance-mail')->namespace('VengeanceMail')->name('vengeancemail.')->group(function() {
    Route::get('send', 'EmailController@sendSpamMail')->name('send.spam.mail');
});
