<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_orders';
    // protected $table = 'orders';

    protected $guarded = [];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'cryptobot_exchange_id', 'id');
    }

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }
}
