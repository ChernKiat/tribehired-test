<?php

namespace App\Http\Controllers\CryptoBot;

use App\Http\Controllers\Controller;
use App\Models\CryptoBot\Exchange;
use App\Models\CryptoBot\Order;
use App\Skins\CryptoBot\CCXTSkin;
use Illuminate\Http\Request;

class CCXTController extends Controller
{
    public function test()
    {
        $ccxt = (new CCXTSkin());
        foreach (Exchange::with('pairsActivated')->where('is_active', 1)->get() as $exchange) {
            $ccxt->setCryptobotExchange($exchange);

            $ccxt->setCryptobotPair($exchange->pairsActivated);
            foreach ($exchange->pairsActivated as $pair) {
                $ccxt->setCryptobotPair($pair);
                // dd($ccxt->fetchTicker());
                // dd($ccxt->fetchOHLCV());
                // dd($ccxt->createOrder(CCXTSkin::TYPE_LIMIT, CCXTSkin::SIDE_BUY, 20, 0.9));
                // dd($ccxt->fetchBalance()['CRYPTO']['free'], $ccxt->fetchBalance()['CRYPTO']['used']);
                dd($ccxt->fetchTrades());
            }
        }

        foreach (Order::with(['exchange', 'pair'])->where('is_own', 1)->whereNull('completed_at')->whereNull('canceled_at')->whereNull('deleted_at')->whereNotNull('created_at')->get() as $order) {
            $ccxt->setCryptobotExchange($order->exchange);
            $ccxt->setCryptobotPair($order->pair);
            foreach ($order->pair as $pair) {
                dd($ccxt->cancelOrder($order->id));
                dd($ccxt->fetchTrades());
            }
        }
        dd('yay');

        \Artisan::call('migrate --path="/database/migrations/CryptoBot/"');
        dd(\Artisan::output(), 'lol');

        return view('test');
    }
}
