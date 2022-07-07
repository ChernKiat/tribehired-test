<?php

namespace App\Http\Controllers\CryptoBot;

use App\Http\Controllers\Controller;
use App\Models\CryptoBot\Exchange;
use App\Models\CryptoBot\Order;
use App\Models\CryptoBot\Pair;
use App\Models\CryptoBot\Strategy;
use App\Skins\CryptoBot\CCXTSkin;
use Illuminate\Http\Request;

class CCXTController extends Controller
{
    public function test()
    {
        // $ccxt = (new CCXTSkin());
        // foreach (Exchange::with('pairsActivated')->where('is_active', 1)->get() as $exchange) {
        //     $ccxt->setCryptobotExchange($exchange);

        //     $ccxt->setCryptobotPair($exchange->pairsActivated);
        //     // dd($ccxt->fetchTickers());
        //     // dd(self::accessLatestDynamicTable()->get(), 'lol');
        //     foreach ($exchange->pairsActivated as $pair) {
        //         $ccxt->setCryptobotPair($pair);
        //         // dd($ccxt->fetchTicker());
        //         // dd($ccxt->fetchOHLCV());
        //         // dd($ccxt->createOrder(CCXTSkin::TYPE_LIMIT, CCXTSkin::SIDE_BUY, 20, 0.9));
        //         dd($ccxt->fetchBalance());
        //     }
        // }

        $ccxt = (new CCXTSkin());
        $ccxt->setCryptobotExchange(Exchange::find(Exchange::BINANCE));
        $ccxt->setCryptobotPair(Pair::where('cryptobot_exchange_id', Exchange::BINANCE)->where('pair', 'RAMP/USDT')->first());
        dd($ccxt->fetchTickers(), 'wtf');

        // \Artisan::call('CCXTPairCommand:retrieve');
        // dd(\Artisan::output(), 'lol');

        CCXTSkin::updatePairs();
        dddd('wtf');

        // $strategy = Strategy::setupCrossPair(Exchange::BINANCE, false);
        $strategy = Strategy::first();
        // dd($strategy, 'lol');
        dd($strategy->run(), 'lol');

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

    public function bug()
    {

    }

    public function removeDuplicatedCryptobotPairs()
    {
        $cryptobotPairs = Pair::select('id')->selectRaw('COUNT(id) AS pair_duplicated')->havingRaw('pair_duplicated > 1')->groupBy('cryptobot_exchange_id')->groupBy('pair')->orderBy('id', 'ASC')->pluck('id')->toArray();

        // Pair::whereIn('id', $cryptobotPairs)->delete();
        Pair::whereIn('id', $cryptobotPairs)->forceDelete();
        // Pair::onlyTrashed()->forceDelete();

        PairStrategy::whereIn('cryptobot_pair_id', $cryptobotPairs)->delete();

        Ohclv::whereIn('cryptobot_pair_id', $cryptobotPairs)->delete();

        Order::whereIn('cryptobot_pair_id', $cryptobotPairs)->delete();

        Ticker::whereIn('cryptobot_pair_id', $cryptobotPairs)->delete();

        Trade::whereIn('cryptobot_pair_id', $cryptobotPairs)->delete();
        // Trade::doesntHave('pair')->whereIn('cryptobot_pair_id', $cryptobotPairs)->delete();

        dd($cryptobotPairs, 'lol');
    }
}
