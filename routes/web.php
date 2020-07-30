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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/spell-check', 'SpellCheckController@index')->name('spell.check');
Route::get('/ws', 'HomeController@ws')->name('ws');
Route::get('/scrabble', 'HomeController@scrabble')->name('scrabble');
Route::get('/math-genius', 'HomeController@mathGenius')->name('math.genius');
Route::get('/net-junkies', 'HomeController@netJunkies')->name('net.junkies');
Route::get('/mash_up', 'HomeController@mashUp')->name('mash.up');
Route::get('/gmtk', 'HomeController@gmtk')->name('gmtk');
Route::get('/test', 'HomeController@test')->name('test');

Route::prefix('seal-chamber')->namespace('SealChamber')->name('sealchamber.')->group(function() {
    Route::get('/', 'PageController@roomCode')->name('room.code');

    Route::prefix('room')->name('room.')->group(function() {
        Route::get('join', 'RoomController@join')->name('join');
    });
});

Route::namespace('Tesseract')->group(function() {
    Route::resource('documents', 'DocumentController')
        ->except('show');
    Route::get('documents/{document}/crop', 'DocumentController@crop')->name('documents.crop');
    Route::get('documents/{document}/export', 'DocumentController@export')->name('documents.export');
});

Route::prefix('system')->namespace('Admin')->group(function() {
    Route::get('documents/{document}/crop', 'RouteTestController@test')->name('test');
});
