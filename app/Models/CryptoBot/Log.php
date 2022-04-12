<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_logs';
    // protected $table = 'logs';

    protected $guarded = [];

    const TYPE_TICKERS_LAST_TICKED  = 1;
    const TYPE_TICKERS_STOPPED      = 2;

    public static function saveLog($log, $type = self::TYPE_TICKERS_LAST_TICKED)
    {
        self::create([
            'type'  => $type,
            'log'   => $log,
        ]);
    }
}
