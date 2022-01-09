<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Strategy;
use Illuminate\Console\Command;

class CCXTStrategyCommand extends Command
{
    protected $signature = 'CCXTStrategyCommand:run';

    protected $description = '';

    public function handle()
    {
        ini_set('memory_limit', '256M');

        while (1) {
            foreach (Strategy::with('pairs')->where('is_active', 1)->get() as $strategy) {
                $strategy->run();
            }

            sleep(5);
        }
    }
}
