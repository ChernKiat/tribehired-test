<?php
namespace App\Models\CryptoBot;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

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
    const TYPE_SHORT_GRID_DCA  = 6;

    const TYPE_DESCRIPTION_LIST = array(
        self::TYPE_BASIC_GRID_DCA  => 'Basic Grid DCA',
        self::TYPE_CROSS_PAIR      => 'Cross Pair',
        self::TYPE_CROSS_EXCHANGE  => 'Cross Exchange',
        self::TYPE_P2P             => 'P2P',
        self::TYPE_PARTNER_API     => 'Partner API',
        self::TYPE_SHORT_GRID_DCA  => 'Short Grid DCA',
    );

    public function orders()
    {
        return $this->hasMany(Order::class, 'cryptobot_strategy_id', 'id');
    }

    public function pairs()
    {
        return $this->belongsToMany(Pair::class, 'cryptobot_pair_strategy', 'cryptobot_strategy_id', 'cryptobot_pair_id')->withTimestamps(); // ->using(PairStrategy::class);
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
                $this->runBasicGridDCA();
                break;
        }
    }

    private function runBasicGridDCA()
    {

    }

    private function runCrossPair()
    {
        dd($this->pairs);
        $group = array();
        foreach ($this->pairs as $pair) {
            $group[$pair->cryptobot_exchange_id][] = $pair;
        }
    }

    public static function updateCrossPair($cryptobot_exchange_id)
    {
        ini_set('max_execution_time', '300');

        $cryptobotCurrencies = Currency::pluck('id', 'name')->toArray();

        $cryptobotPairs = Pair::where(function ($query) {
                                    $query->whereNull('cryptobot_quote_currency_id')->orWhereNull('cryptobot_base_currency_id');
                                })->where('is_active', 1)->where('cryptobot_exchange_id', $cryptobot_exchange_id)->get();
        foreach ($cryptobotPairs as $pair) {
            $currencies = explode('/', $pair->pair);
            foreach ($currencies as $key => $currency) {
                if (array_key_exists($cryptobotCurrencies, $currency)) {
                    $currencies[$key] = $cryptobotCurrencies[$currency];
                } else {
                    $currencies[$key] = Currency::updateOrCreate(['name' => $currency])->id;
                }
                if ($key == 0) {
                    $cryptobot_currency_id = 'cryptobot_base_currency_id';
                } elseif ($key == 1) {
                    $cryptobot_currency_id = 'cryptobot_quote_currency_id';
                }
                if (is_null($pair->{$cryptobot_currency_id})) {
                    $pair->{$cryptobot_currency_id} = $currencies[$key]->id;
                    $pair->save();
                }
            }
        }

        $cryptobotPairs = Pair::where('is_active', 1)->where('cryptobot_exchange_id', $cryptobot_exchange_id)->get();

        $groups = array();
        foreach ($cryptobotPairs as $pair) {
            $groups[$pair->cryptobot_base_currency_id][$pair->cryptobot_quote_currency_id] = $pair;
            $groups[$pair->cryptobot_quote_currency_id][$pair->cryptobot_base_currency_id] = $pair;
        }

        $cryptobot_currencies = Currency::doesntHave('strategy2')->pluck('id')->toArray();
        foreach ($groups as $key => $group) {
            if (count($group) == 1) { // need at least 2 to be useful
                foreach ($group as $row => $pair) {
                    unset($groups[$row][$key]);
                    unset($groups[$key]);
                }
            } elseif (count($group) < 10 && !in_array($key, $cryptobot_currencies)) {
                unset($groups[$key]);
            }
        }

        $cryptobotExchange = Exchange::find($cryptobot_exchange_id);
        $cryptobot_currencies = Currency::pluck('name', 'id')->toArray();
        foreach ($groups as $key => $group) {
            $cryptobotStrategy  = self::updateOrCreate([
                'name'                   => strtoupper($cryptobotExchange->exchange) . "_{$cryptobot_currencies[$key]}",
            ], [
                'name'                   => strtoupper($cryptobotExchange->exchange) . "_{$cryptobot_currencies[$key]}",
                'type'                   => self::TYPE_CROSS_PAIR,
                'is_active'              => 0,
                'cryptobot_currency_id'  => $key,
                'created_at'             => Carbon::now(),
                'updated_at'             => Carbon::now(),
            ]);

            foreach ($group as $row => $pair) {
                DB::table('cryptobot_pair_strategy')->updateOrInsert([
                    'cryptobot_pair_id'      => $pair->id,
                    'cryptobot_strategy_id'  => $cryptobotStrategy->id,
                ], [
                    'cryptobot_pair_id'      => $pair->id,
                    'cryptobot_strategy_id'  => $cryptobotStrategy->id,
                    'is_base'                => 0,
                    'created_at'             => Carbon::now(),
                    'updated_at'             => Carbon::now(),
                ]);
                // $cryptobot_pair_strategy[] = array('cryptobot_pair_id' => $pair->id, 'cryptobot_strategy_id' => $cryptobotStrategy->id, 'is_base' => 0);
            }
            $temp = array_keys($group);
            foreach ($temp as $row) {
                foreach ($temp as $index) {
                    if ($row > $index) {
                        if (array_key_exists($row, $groups) && array_key_exists($index, $groups[$row])) {
                            DB::table('cryptobot_pair_strategy')->updateOrInsert([
                                'cryptobot_pair_id'      => $groups[$row][$index]->id,
                                'cryptobot_strategy_id'  => $cryptobotStrategy->id,
                            ], [
                                'cryptobot_pair_id'      => $groups[$row][$index]->id,
                                'cryptobot_strategy_id'  => $cryptobotStrategy->id,
                                'is_base'                => 1,
                                'created_at'             => Carbon::now(),
                                'updated_at'             => Carbon::now(),
                            ]);
                            // $cryptobot_pair_strategy[] = array('cryptobot_pair_id' => $groups[$row][$index]->id, 'cryptobot_strategy_id' => $cryptobotStrategy->id, 'is_base' => 1);
                        }
                    }
                }
            }

            $cryptobotStrategy->cryptobot_currency_id = $key;
            $cryptobotStrategy->save();

            // DB::table('cryptobot_pair_strategy')->insert($cryptobot_pair_strategy);
            // DB::table('cryptobot_pair_strategy')->upsert($cryptobot_pair_strategy, ['cryptobot_pair_id', 'cryptobot_strategy_id'], ['is_base']);
        }

        return true;
    }

    public static function setupCrossPair($cryptobot_exchange_id)
    {

    }

    public static function removeCrossPair($cryptobot_exchange_id)
    {
        $cryptobot_strategies = self::whereHas('pairs', function ($query) use ($cryptobot_exchange_id) {
                                                $query->where('cryptobot_exchange_id', $cryptobot_exchange_id);
                                            })->where('type', self::TYPE_CROSS_PAIR)->pluck('id')->toArray();

        // find for active orders
        if (self::whereIn('id', $cryptobot_strategies)->whereHas('orders', function ($query) {
                                                                $query->where('is_own', 1)->whereNull('completed_at')->whereNull('canceled_at')->whereNull('deleted_at');
                                                            })->exists()) { return false; }

        self::whereIn('id', $cryptobot_strategies)->update(['is_active' => 0]);

        sleep(5);

        // find for active orders
        $cryptobotOrdersBuilder = Order::whereIn('cryptobot_strategy_id', $cryptobot_strategies)->where('is_own', 1)->whereNull('completed_at')->whereNull('canceled_at')->whereNull('deleted_at');

        if (!$cryptobotOrdersBuilder->exists()) {
            DB::table('cryptobot_pair_strategy')->whereIn('cryptobot_strategy_id', $cryptobot_strategies)->delete();
            Order::whereIn('cryptobot_strategy_id', $cryptobot_strategies)->update(['cryptobot_strategy_id' => null]);
            self::whereIn('id', $cryptobot_strategies)->delete();

            return true;
        } else {
            self::whereIn('id', $cryptobot_strategies)->update(['is_active' => 1]);

            return false;
        }
    }
}
