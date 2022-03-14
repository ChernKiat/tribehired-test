<?php
namespace App\Models\CryptoBot;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
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
        static::deleting(function(StoredFile $model) {
            $model->is_active   = 0;
            $model->deleted_at  = Carbon::now();
            $model->save();
        });
    }
}
