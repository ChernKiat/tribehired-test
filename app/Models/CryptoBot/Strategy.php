<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Strategy extends Model
{
    use SoftDeletes;

    // protected $connection = 'mysql';
    protected $table = 'cryptobot_strategies';
    // protected $table = 'strategies';

    protected $guarded = [];

    const GRID_DCA  = 1;

    const TYPE_DESCRIPTION_LIST = array(
        self::GRID_DCA  => 'Grid DCA',
    );

    public function orders()
    {
        return $this->hasMany(Order::class, 'cryptobot_strategy_id', 'id');
    }

    public function pairs()
    {
        return $this->belongsToMany(Pair::class, 'cryptobot_pair_strategy', 'cryptobot_pair_id', 'cryptobot_strategy_id')->withTimestamps(); // ->using(PairStrategy::class);
    }
}
