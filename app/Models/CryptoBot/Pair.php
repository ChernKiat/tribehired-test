<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pair extends Model
{
    use SoftDeletes;

    // protected $connection = 'mysql';
    protected $table = 'cryptobot_pairs';
    // protected $table = 'pairs';

    protected $guarded = [];

    // public function dynamicTickers()
    // {
    //     return $this->hasMany(DynamicTicker::class, 'cryptobot_pair_id', 'id');
    // }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'cryptobot_exchange_id', 'id');
    }
}
