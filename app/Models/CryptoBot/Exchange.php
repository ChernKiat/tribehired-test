<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    use SoftDeletes;

    // protected $connection = 'mysql';
    protected $table = 'cryptobot_exchanges';
    // protected $table = 'exchanges';

    protected $guarded = [];

    const BINANCE  = 7;

    public function pairs()
    {
        return $this->hasMany(Pair::class, 'cryptobot_exchange_id', 'id');
    }

    public function pairsActivated()
    {
        return $this->pairs()->where('is_active', 1);
    }

    public function pairsDeactivated()
    {
        return $this->pairs()->where('is_active', 0);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($model) {
            $model->is_active   = 0;
            $model->save();
        });
    }
}
