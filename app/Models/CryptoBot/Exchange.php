<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_exchanges';
    // protected $table = 'exchanges';

    protected $guarded = [];

    public function pairs()
    {
        return $this->hasMany(Pair::class, 'cryptobot_exchange_id', 'id');
    }

    public function pairsActivated()
    {
        return $this->pairs()->where('is_active', 1);
    }
}
