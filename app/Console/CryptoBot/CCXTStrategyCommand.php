<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Strategy;
use Illuminate\Console\Command;

class CCXTStrategyCommand extends Command
{
    protected $signature = 'CCXTStrategyCommand:run';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ini_set('memory_limit', '256M');

        while (Config::get('bot.is_active.CCXTStrategyCommand:run')) {
            foreach (Strategy::with('pairs')->where('is_active', 1)->get() as $strategy) {
                $strategy->run();
            }

            sleep(5);
        }
    }
}
