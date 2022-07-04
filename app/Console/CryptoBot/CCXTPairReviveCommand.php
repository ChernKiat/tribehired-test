<?php

namespace App\Console\CryptoBot;

use App\Models\CryptoBot\Exchange;
use App\Skins\CryptoBot\CCXTSkin;
use Config;
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

        if (Config::get('bot.is_active.CCXTPairCommand:revive')) {
            CCXTSkin::updatePairs();
        }
    }
}
