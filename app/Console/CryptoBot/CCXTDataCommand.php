<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Exchange;
use App\Skins\CryptoBot\CCXTSkin;
use Carbon\Carbon;
use Config;
use Illuminate\Console\Command;

class CCXTDataCommand extends Command
{
    protected $signature = 'CCXTDataCommand:update';

    protected $description = '';

    public function handle()
    {
        ini_set('memory_limit', '256M');

        while (1) {
            $ccxt = (new CCXTSkin());
            foreach (Exchange::with('pairsActivated')->where('is_active', 1)->get() as $exchange) {
                $ccxt->setCryptobotExchange($exchange);
                foreach ($exchange->pairsActivated as $pair) {
                    $ccxt->setCryptobotPair($pair);
                    // $ccxt->fetchTickers();
                    // $ccxt->fetchOHLCV();
                    dd($ccxt->fetchTrades());
                }
            }

            sleep(5);
        }
    }
}
