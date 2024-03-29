<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CryptoBot\CCXTPairRetrieveCommand::class,
        CryptoBot\CCXTPairReviveCommand::class,
        CryptoBot\CCXTStrategyCommand::class,
        All\DatabaseBackupCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('CCXTPairCommand:retrieve')->withoutOverlapping()->everyMinute();
        $schedule->command('CCXTPairCommand:revive')->withoutOverlapping()->dailyAt('00:00');
        // $schedule->command('CCXTStrategyCommand:run')->withoutOverlapping()->everyMinute();
        $schedule->command('DatabaseCommand:backup')->withoutOverlapping()->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
