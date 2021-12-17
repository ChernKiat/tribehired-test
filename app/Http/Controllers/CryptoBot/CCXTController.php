<?php

namespace App\Http\Controllers\CryptoBot;

use App\Skins\CryptoBot\CCXTSkin;
use Illuminate\Http\Request;

class CCXTController extends Controller
{
    public function test()
    {
        CCXTSkin::updateExchanges();
        dd('lol');

        return view('test');
    }
}
