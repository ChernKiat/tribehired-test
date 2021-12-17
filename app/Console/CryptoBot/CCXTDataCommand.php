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
            foreach (Exchange::with('pairsActivated')->where('is_active', 1)->get() as $exchange) {
                foreach ($exchange->pairsActivated as $pair) {
                    (new CCXTSkin())->fetchTickers($pair, $exchange)->fetchOHLCV($pair, $exchange);
                }
            }

            sleep(5);
        }
    }
}
