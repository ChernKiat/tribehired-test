<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('research-development')->namespace('ResearchDevelopment')->name('researchdevelopment.')->group(function() {
    Route::get('one', 'TribeHiredController@one')->name('one');
    Route::post('two', 'TribeHiredController@two')->name('two');
});
