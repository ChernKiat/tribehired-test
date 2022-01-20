<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Exchange;
use App\Skins\CryptoBot\CCXTSkin;
use Illuminate\Console\Command;

class CCXTPairReviveCommand extends Command
{
    // protected $name = 'CCXTPairCommand:revive';

    protected $signature = 'CCXTPairCommand:revive {--pair}';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ini_set('memory_limit', '256M');

        $ccxt = (new CCXTSkin());
        foreach (Exchange::with('pairsDeactivated')->where('is_active', 1)->get() as $exchange) {
            $ccxt->setCryptobotExchange($exchange);
            $ccxt->setCryptobotPair($exchange->pairsDeactivated);
            $ccxt->fetchTickers();
        }
    }
}
