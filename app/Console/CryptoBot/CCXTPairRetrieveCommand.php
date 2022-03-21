<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Exchange;
use App\Skins\CryptoBot\CCXTSkin;
use Config;
use Illuminate\Console\Command;

class CCXTPairRetrieveCommand extends Command
{
    // protected $name = 'CCXTPairCommand:retrieve';

    protected $signature = 'CCXTPairCommand:retrieve {--pair}';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ini_set('memory_limit', '256M');

        while (Config::get('bot.is_active.CCXTPairCommand:retrieve')) {
            // $ccxt = (new CCXTSkin(array('mode' => CCXTSkin::MODE_DYNAMIC)));
            $ccxt = (new CCXTSkin());
            foreach (Exchange::with('pairsActivated')->where('is_active', 1)->get() as $exchange) {
                $ccxt->setCryptobotExchange($exchange);
                $ccxt->setCryptobotPair($exchange->pairsActivated);
                $ccxt->fetchTickers();
            }

            sleep(5);
        }
    }
}
