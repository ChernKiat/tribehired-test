<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_currencies';
    // protected $table = 'currencies';

    protected $guarded = [];

    public function quotePairs()
    {
        return $this->hasMany(Pair::class, 'cryptobot_quote_currency_id', 'id');
    }

    public function basePairs()
    {
        return $this->hasMany(Pair::class, 'cryptobot_base_currency_id', 'id');
    }

    public function strategy2()
    {
        return $this->hasOne(Strategy::class, 'cryptobot_currency_id', 'id');
    }
}
