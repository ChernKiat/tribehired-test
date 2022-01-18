<?php
namespace App\Models\CryptoBot;

use App\Traits\CryptoBot\DynamicDatabaseTrait;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DynamicTicker extends Model
{
    use DynamicDatabaseTrait;

    // protected $connection = 'mysql';
    // protected $table = 'cryptobot_tickers_{}w_data';
    // protected $table = 'tickers_{}w_data';

    protected $guarded = [];

    const TIMESTAMP_1_HOUR  = 3600;
    const TIMESTAMP_1_DAY   = 86400;
    const TIMESTAMP_1_WEEK  = 604800;

    const TIME_100000_SECOND     = '1d 3h 46m 40s';
    const TIME_500000_SECOND     = '5d 18h 53m 20s';
    const TIME_800000_SECOND     = '9d 6h 13m 20s';
    const TIME_1000000_SECOND    = '11d 13h 46m 40s';
    const TIME_5000000_SECOND    = '1m 29d 20h 53m 20s';
    const TIME_10000000_SECOND   = '3m 26d 17h 46m 40s';
    const TIME_100000000_SECOND  = '3y 2m 2d 9h 46m 40s';

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }

    public static function createDynamicTable($timestamp)
    {
        $timestamp_group = self::getTimestampGroup($timestamp);

        self::existDynamicTable("cryptobot_tickers_{$timestamp_group}w_data");

        // dd(self::from("cryptobot_tickers_{$timestamp_group}w_data"));
        // dd((new self)->setTable("cryptobot_tickers_{$timestamp_group}w_data"));
        return (new self)->setTable("cryptobot_tickers_{$timestamp_group}w_data");
    }

    public static function existDynamicTable($table_name)
    {
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function(Blueprint $table)
            {
                $table->increments('id');
                $table->unsignedInteger('cryptobot_exchange_id'); // ->index();
                $table->unsignedInteger('cryptobot_pair_id'); // ->index();
                $table->bigInteger('timestamp'); // ->index();
                $table->timestamp('datetime')->nullable();
                $table->decimal('high', 23, 10)->nullable();
                $table->decimal('low', 23, 10)->nullable();
                $table->decimal('bid', 23, 10)->nullable();
                $table->decimal('bid_volume', 23, 10)->nullable();
                $table->decimal('ask', 23, 10)->nullable();
                $table->decimal('ask_volume', 23, 10)->nullable();
                $table->decimal('vwap', 23, 10)->nullable(); // volume-weighted average price
                $table->decimal('open', 23, 10)->nullable();
                $table->decimal('close', 23, 10)->nullable();
                // $table->decimal('first', 23, 10)->nullable();
                $table->decimal('last', 23, 10)->nullable();
                $table->decimal('change', 23, 10)->nullable();
                $table->decimal('percentage', 23, 10)->nullable();
                $table->decimal('average', 23, 10)->nullable();
                $table->decimal('base_volume', 23, 10)->nullable();
                $table->decimal('quote_volume', 23, 10)->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->unique(['cryptobot_exchange_id', 'cryptobot_pair_id', 'timestamp'], 'exchange_pair_timestamp');
            });
        }
    }

    public static function getTimestampGroup($timestamp)
    {
        return intval($timestamp / self::TIMESTAMP_1_WEEK);
    }

    public static function accessLatestDynamicTable()
    {
        $dynamicTablesList = array_map(function ($table_name) {
                                        return intval(explode('_', $table_name)[2] ?? 0);
                                    }, array_filter(DB::connection()->getDoctrineSchemaManager()->listTableNames(), function ($table_name) {
                                        return (stripos($table_name, 'cryptobot_tickers_') !== false);
                                    }));

        return !empty($dynamicTablesList) ? (new self)->setTable('cryptobot_tickers_' . max($dynamicTablesList) . 'w_data') : false;
    }
}
