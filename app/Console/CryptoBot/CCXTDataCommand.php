<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Exchange;
use App\Skins\CryptoBot\CCXTSkin;
use Illuminate\Console\Command;

class CCXTDataCommand extends Command
{
    // protected $name = 'CCXTDataCommand:update';

    protected $signature = 'CCXTDataCommand:update {--pair}';

    protected $description = '';

    public function handle()
    {
        ini_set('memory_limit', '256M');

        while (1) {
            $ccxt = (new CCXTSkin());
            foreach (Exchange::with('pairsActivated')->where('is_active', 1)->get() as $exchange) {
                $ccxt->setCryptobotExchange($exchange);
                $ccxt->setCryptobotPair($exchange->pairsActivated);
                $ccxt->fetchTickers(CCXTSkin::MODE_DYNAMIC);
            }

            sleep(5);
        }
    }
}
