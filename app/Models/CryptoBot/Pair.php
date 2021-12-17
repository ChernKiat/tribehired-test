<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_pairs';
    // protected $table = 'pairs';

    protected $guarded = [];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'cryptobot_exchange_id', 'id');
    }
}
