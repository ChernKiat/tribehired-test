<?php

namespace App\Http\Controllers\VanguardSystem\CryptoBot;

use App\Http\Controllers\Controller;
use App\Models\CryptoBot\Currency;
use App\Models\CryptoBot\Exchange;
use Illuminate\Http\Request;

class CCXTController extends Controller
{
    public function test(Request $request)
    {
        $user = auth()->user() ?? null;

        $exchanges = Exchange::pluck('name', 'id')->toArray();
        $currencies = Currency::pluck('name', 'id')->toArray();

        return view('modules.vanguard_system.crypto_bot.show', compact('user', 'exchanges', 'currencies'));
    }

    public function strategyCrossExchange(Request $request)
    {
        $user = auth()->user() ?? null;

        return view('modules.vanguard_system.crypto_bot.show', compact('user'));
    }
}
