<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_tickers';
    // protected $table = 'tickers';

    protected $fillable = [
    ];

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }
}
