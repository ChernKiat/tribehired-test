<?php
namespace App\Models\CryptoBot;

use App\Traits\CryptoBot\DynamicDatabaseTrait;
use DB;
use Illuminate\Database\Eloquent\Model;

class DynamicTicker extends Model
{
    use DynamicDatabaseTrait;

    // protected $connection = 'mysql';
    // protected $table = 'cryptobot_tickers_{}_data';
    // protected $table = 'tickers_{}_data';

    protected $guarded = [];

    const 1_HOUR  = 3600;
    const 1_DAY   = 86400;
    const 1_WEEK  = 604800;

    const 100000_SECOND     = '1d 3h 46m 40s';
    const 500000_SECOND     = '5d 18h 53m 20s';
    const 800000_SECOND     = '9d 6h 13m 20s';
    const 1000000_SECOND    = '11d 13h 46m 40s';
    const 5000000_SECOND    = '1m 29d 20h 53m 20s';
    const 10000000_SECOND   = '3m 26d 17h 46m 40s';
    const 100000000_SECOND  = '3y 2m 2d 9h 46m 40s';

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }

    public static function createDynamicTable($timestamp)
    {
        $timestamp_group = self::getTimestampGroup($timestamp);

        self::existDynamicTable("cryptobot_tickers_{$timestamp_group}_data");

        return self::from("cryptobot_tickers_{$timestamp_group}_data");
    }

    public static function getTimestampGroup($timestamp)
    {
        return intval($timestamp / self::1_WEEK) . 'w';
    }

    public static function accessLatestDynamicTable()
    {
        $total = count(array_filter(DB::connection()->getDoctrineSchemaManager()->listTableNames(), function ($table_name) {
                        return (stripos($table_name, 'cryptobot_tickers_') !== false);
                    }));

        return $total !== 0 ? self::from("cryptobot_tickers_{$total}w_data") : false;
    }
}
