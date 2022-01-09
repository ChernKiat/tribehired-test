<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CryptoBot\DynamicDatabaseTrait;

class DynamicTicker extends Model
{
    use DynamicDatabaseTrait;

    // protected $connection = 'mysql';
    protected $table = 'cryptobot_tickers';
    // protected $table = 'tickers';

    protected $guarded = [];

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }
}
