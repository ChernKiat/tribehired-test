<?php
namespace App\Models\CryptoBot;

use DB;
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
            case self::TYPE_SHORT_GRID_DCA:
                break;
            case self::TYPE_PARTNER_API: // Duckside DAO
                break;
            case self::TYPE_P2P:
                break;
            case self::TYPE_CROSS_EXCHANGE:
                break;
            case self::TYPE_CROSS_PAIR:
                $this->runCrossPair();
                break;
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

    public static function setupCrossPair($cryptobot_exchange_id)
    {
        $cryptobot_strategies = self::whereHas('pairs', function ($query) use ($cryptobot_exchange_id) {
                                                $query->where('cryptobot_exchange_id', $cryptobot_exchange_id);
                                            })->where('type', self::TYPE_CROSS_PAIR)->pluck('id')->toArray();

        if (self::whereIn('id', $cryptobot_strategies)->whereHas('orders', function ($query) {
                                                                $query->where('is_own', 1)->whereNull('completed_at')->whereNull('canceled_at')->whereNull('deleted_at');
                                                            })->exists()) { return false; }

        self::whereIn('id', $cryptobot_strategies)->update(['is_active' => 0]);

        sleep(30);

        $cryptobotOrdersBuilder = Order::whereIn('cryptobot_strategy_id', $cryptobot_strategies)->where('is_own', 1)->whereNull('completed_at')->whereNull('canceled_at')->whereNull('deleted_at');

        if (!$cryptobotOrdersBuilder->exists()) {
            DB::table('cryptobot_pair_strategy')->whereIn('cryptobot_strategy_id', $cryptobot_strategies)->delete();
            Order::whereIn('cryptobot_strategy_id', $cryptobot_strategies)->update(['cryptobot_strategy_id' => null]);
            self::whereIn('id', $cryptobot_strategies)->delete();

            $cryptobotPairs = Pair::where(function ($query) use ($request) {
                                        $query->whereNull('cryptobot_quote_currency_id')->orWhereNull('cryptobot_base_currency_id');
                                    })->where('is_active', 1)->where('cryptobot_exchange_id', $cryptobot_exchange_id)->get();
            foreach ($cryptobotPairs as $pair) {
                $currencies = explode('/', $pair->pair);
                foreach ($currencies as $key => $pair) {
                    $currencies[$key] = Currency::updateOrCreate([
                        'name'  => $currencies[$key],
                    ], [
                        'name'  => $currencies[$key],
                    ]);
                    if ($key == 0) {
                        $cryptobot_currency_id = 'cryptobot_base_currency_id';
                    } elseif ($key == 1) {
                        $cryptobot_currency_id = 'cryptobot_quote_currency_id';
                    }
                    if (is_null($pair->{$cryptobot_currency_id})) { $pair->{$cryptobot_currency_id} = $currencies[$key]; }
                }
            }

            $groups = array();
            // foreach (Currency::pluck('name', 'id')->toArray() as $key => $currency) {
            foreach ($cryptobotPairs as $pair) {
                $groups[$pair->cryptobot_base_currency_id][$pair->cryptobot_quote_currency_id] = $pair->id;
                $groups[$pair->cryptobot_quote_currency_id][$pair->cryptobot_base_currency_id] = $pair->id;
            }
            dd($groups);

            $possible_connections = array();
            foreach ($groups as $group) {
                $possible_connections = array();
                $connections[$pair->cryptobot_base_currency_id] = $pair->id;
                $connections[$pair->cryptobot_quote_currency_id] = $pair->id;
                ->where('cryptobot_exchange_id', $cryptobot_exchange_id)
            }

            foreach ($cryptobot_strategies as $key => $value) {
                $cryptobotStrategy = self::updateOrCreate([
                    'name'       => $value['strategy'],
                ], [
                    'name'       => $value['strategy'],
                    'type'       => self::TYPE_CROSS_PAIR,
                    'is_active'  => 1,
                ]);

                foreach ($value as $value) {
                    $value['cryptobot_strategy_id'] = $cryptobotStrategy->id;
                }

                DB::table('cryptobot_pair_strategy')->upsert([
                    ['departure' => 'Oakland', 'destination' => 'San Diego', 'price' => 99],
                    ['departure' => 'Chicago', 'destination' => 'New York', 'price' => 150]
                ], ['cryptobot_pair_id', 'cryptobot_strategy_id'], ['order']);
            }

            return true;
        } else {
            self::whereIn('id', $cryptobot_strategies)->update(['is_active' => 1]);
            return false;
        }
    }
}
