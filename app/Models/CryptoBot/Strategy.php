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

    const TYPE_BASIC_GRID_DCA  = 1;
    const TYPE_CROSS_PAIR      = 2;
    const TYPE_CROSS_EXCHANGE  = 3;
    const TYPE_P2P             = 4;
    const TYPE_PARTNER_API     = 5;

    const TYPE_DESCRIPTION_LIST = array(
        self::TYPE_BASIC_GRID_DCA  => 'Basic Grid DCA',
        self::TYPE_CROSS_PAIR      => 'Cross Pair',
        self::TYPE_CROSS_EXCHANGE  => 'Cross Exchange',
        self::TYPE_P2P             => 'P2P',
        self::TYPE_PARTNER_API     => 'Partner API',
    );

    public function orders()
    {
        return $this->hasMany(Order::class, 'cryptobot_strategy_id', 'id');
    }

    public function pairs()
    {
        return $this->belongsToMany(Pair::class, 'cryptobot_pair_strategy', 'cryptobot_pair_id', 'cryptobot_strategy_id')->withTimestamps(); // ->using(PairStrategy::class);
    }


    public function run()
    {
        switch ($this->type) {
            case self::TYPE_PARTNER_API:
                break;
            case self::TYPE_P2P:
                break;
            case self::TYPE_CROSS_EXCHANGE:
                break;
            case self::TYPE_CROSS_PAIR:
                break;
                $this->runCrossPair()
            case self::TYPE_BASIC_GRID_DCA:
            default:
                $this->runBasicGridDCA()
                break;
        }
    }

    private function runBasicGridDCA()
    {

    }

    private function runCrossPair()
    {
        dd($this->pairs->groupBy('cryptobot_exchange_id'));

        $group = array();
        foreach ($this->pairs as $pair) {
            $group[$pair->cryptobot_exchange_id][] = $pair;
        }
    }
}
