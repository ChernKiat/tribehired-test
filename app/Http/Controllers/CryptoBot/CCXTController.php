<?php

namespace App\Http\Controllers\CryptoBot;

use App\Http\Controllers\Controller;
use App\Skins\CryptoBot\CCXTSkin;
use Illuminate\Http\Request;

class CCXTController extends Controller
{
    public function test()
    {
        \Artisan::call('migrate --path="/database/migrations/CryptoBot/"');
        dd(\Artisan::output(), 'lol');

        return view('test');
    }
}
