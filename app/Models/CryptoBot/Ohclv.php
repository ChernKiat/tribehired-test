<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Ohclv extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_ohlcvs';
    // protected $table = 'ohlcvs';

    protected $fillable = [
    ];

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }
}
