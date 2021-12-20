<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_trades';
    // protected $table = 'trades';

    protected $guarded = [];

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }
}
