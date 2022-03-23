<?php
namespace App\Models\CryptoBot;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Log;

class Strategy extends Model
{
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
        return $this->belongsToMany(Pair::class, 'cryptobot_pair_strategy', 'cryptobot_strategy_id', 'cryptobot_pair_id')->withPivot('is_base')->withTimestamps(); // ->using(PairStrategy::class);
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
                $this->runCrossExchange();
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
        $strategy = Strategy::with(['pairs.ticker'])->where('is_active', 1)->first();
        $markets = array();
        foreach ($strategy->pairs as $pair) {
            if ($pair->pivot->is_base == 0) {
                // from $strategy->cryptobot_currency_id's perspective [you want its partner, you want it]
                if ($pair->cryptobot_quote_currency_id == $strategy->cryptobot_currency_id) {
                    $markets[$pair->cryptobot_base_currency_id] = ['cryptobot_quote_currency_id', $pair->ticker->bid, $pair->ticker->ask, $pair->ticker->ask, $pair->pair, $pair];
                } else {
                    $markets[$pair->cryptobot_quote_currency_id] = ['cryptobot_base_currency_id', $pair->ticker->bid, $pair->ticker->ask, $pair->ticker->ask, $pair->pair, $pair];
                }
            }
        }
        $actions = array();
        foreach ($strategy->pairs as $pair) {
            if ($pair->pivot->is_base == 1) {
                    $actions[] = [$pair, $pair->pair, $pair->ticker->bid, $pair->ticker->ask, $markets[$pair->cryptobot_base_currency_id], $markets[$pair->cryptobot_quote_currency_id]];
                // if ($markets[$pair->cryptobot_quote_currency_id]  $markets[$pair->cryptobot_base_currency_id]) {
                // if ($markets[$pair->cryptobot_quote_currency_id][0] * $pair->ticker->ask > $markets[$pair->cryptobot_base_currency_id][0]) {
                //     $actions['buy'] = [$pair, $pair->pair, $pair->ticker->bid, $pair->ticker->ask, $markets[$pair->cryptobot_base_currency_id], $markets[$pair->cryptobot_quote_currency_id]];
                // } elseif ($markets[$pair->cryptobot_quote_currency_id][0] * $pair->ticker->bid > $markets[$pair->cryptobot_base_currency_id][0]) {
                //     $actions['sell'] = [$pair, $pair->pair, $pair->ticker->bid, $pair->ticker->ask, $markets[$pair->cryptobot_base_currency_id], $markets[$pair->cryptobot_quote_currency_id]];
                // }
            }
        }
        dd($actions, 'lol');
    }

    public static function setupCrossExchange($cryptobot_currency_id = null)
    {
        if (!is_null($cryptobot_currency_id)) {
            $cryptobotExchanges = Exchange::with(['pairsActivated' => function ($query) use ($cryptobot_currency_id) {
                                            $query->where(function ($q) use ($cryptobot_currency_id) {
                                                $q->where('cryptobot_quote_currency_id', $cryptobot_currency_id);
                                                $q->orWhere('cryptobot_base_currency_id', $cryptobot_currency_id);
                                            });
                                        }])->where('is_active', 1)->get();
        } else {
            $cryptobotExchanges = Exchange::with(['pairsActivated'])->where('is_active', 1)->get();
        }

        $groups = array();
        foreach ($cryptobotExchanges as $exchange) {
            foreach ($exchange->pairsActivated as $pair) {
                // $groups[$pair->cryptobot_base_currency_id][$pair->cryptobot_quote_currency_id]['cryptobot_base_currency_id'][$exchange->id] = $pair;
                // $groups[$pair->cryptobot_quote_currency_id][$pair->cryptobot_base_currency_id]['cryptobot_quote_currency_id'][$exchange->id] = $pair;
                $groups[$pair->cryptobot_base_currency_id][$pair->cryptobot_quote_currency_id]['cryptobot_base_currency_id'][$exchange->id] = $pair;
                $groups[$pair->cryptobot_quote_currency_id][$pair->cryptobot_base_currency_id]['cryptobot_quote_currency_id'][$exchange->id] = $pair;
            }
        }

        foreach ($groups as $key => $group) {
            foreach ($group as $index => $partner) {
                foreach ($partner as $value => $side) {
                    if (count($groups[$key][$index][$value]) < 2) { // need at least 2 to be useful
                        unset($groups[$key][$index][$value]);
                    }
                }
                if (count($groups[$key][$index]) == 0) { // need at least 2 to be useful
                    unset($groups[$key][$index]);
                }
            }
            if (count($groups[$key]) == 0) { // need at least 2 to be useful
                unset($groups[$key]);
            }
        }

        $cryptobot_currencies = Currency::pluck('name', 'id')->toArray();
        foreach ($groups as $key => $group) {
            foreach ($group as $index => $partner) {
                foreach ($partner as $value => $side) {
                    $cryptobot_pair_strategy = array();

                    $name = array($cryptobot_currencies[$key], $cryptobot_currencies[$index]);
                    sort($name);
                    $name = implode('_', $name) . '_' . ($value == 'cryptobot_quote_currency_id' ? 'QUOTE' : 'BASE');

                    $cryptobotStrategy  = self::updateOrCreate([
                        'name'                   => $name,
                    ], [
                        'name'                   => $name,
                        'type'                   => self::TYPE_CROSS_EXCHANGE,
                        'is_active'              => 0,
                        // 'is_same'                => ==,
                        'cryptobot_currency_id'  => $cryptobot_currency_id ?? $key,
                        'updated_at'             => Carbon::now(),
                    ]);

                    foreach ($side as $pair) {
                        $cryptobot_pair_strategy[$pair->id] = array('is_base' => 0);
                    }
                    $cryptobotStrategy->pairs()->sync($cryptobot_pair_strategy);
                }
            }
        }

        return true;
    }

    public static function setupCrossPair($cryptobot_exchange_id)
    {

    }

    public static function updateCrossPair($cryptobot_exchange_id)
    {
        ini_set('max_execution_time', '300');

        $cryptobotExchange = Exchange::find($cryptobot_exchange_id);
        $cryptobotPairs = Pair::where('is_active', 1)->where('cryptobot_exchange_id', $cryptobot_exchange_id)->whereNotNull('cryptobot_quote_currency_id')->whereNotNull('cryptobot_base_currency_id')->get();

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

        $cryptobot_currencies = Currency::pluck('name', 'id')->toArray();
        $cryptobot_pair_strategy = array();
        foreach ($groups as $key => $group) {
            $cryptobotStrategy  = self::updateOrCreate([
                'name'                   => strtoupper($cryptobotExchange->exchange) . "_{$cryptobot_currencies[$key]}",
            ], [
                'name'                   => strtoupper($cryptobotExchange->exchange) . "_{$cryptobot_currencies[$key]}",
                'type'                   => self::TYPE_CROSS_PAIR,
                'is_active'              => 1,
                'cryptobot_currency_id'  => $key,
                'updated_at'             => Carbon::now(),
            ]);

            foreach ($group as $pair) {
                $cryptobot_pair_strategy[$pair->id] = array('is_base' => 0);
            }

            $temp = array_keys($group);
            foreach ($temp as $row) {
                foreach ($temp as $index) {
                    if ($row > $index) {
                        if (array_key_exists($row, $groups) && array_key_exists($index, $groups[$row])) {
                            $cryptobot_pair_strategy[$groups[$row][$index]->id] = array('is_base' => 1);
                        }
                    }
                }
            }
            $cryptobotStrategy->pairs()->sync($cryptobot_pair_strategy);

            // DB::table('cryptobot_pair_strategy')->insert($cryptobot_pair_strategy);
            // DB::table('cryptobot_pair_strategy')->upsert($cryptobot_pair_strategy, ['cryptobot_pair_id', 'cryptobot_strategy_id'], ['is_base']);
        }

        return true;
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
