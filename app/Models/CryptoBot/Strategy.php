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
                $this->runBasicGridDCA();
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

    public static function setupCrossPair($cryptobot_exchange_id, $enable_update_currencies = true)
    {
        ini_set('max_execution_time', '300');

        if ($enable_update_currencies) {
            $cryptobotPairs = Pair::where(function ($query) {
                                        $query->whereNull('cryptobot_quote_currency_id')->orWhereNull('cryptobot_base_currency_id');
                                    })->where('is_active', 1)->where('cryptobot_exchange_id', $cryptobot_exchange_id)->get();
            foreach ($cryptobotPairs as $pair) {
                $currencies = explode('/', $pair->pair);
                foreach ($currencies as $key => $currency) {
                    $currencies[$key] = Currency::updateOrCreate([
                        'name'  => $currency,
                    ], [
                        'name'  => $currency,
                    ]);
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
        }

        $cryptobotPairs = Pair::where('is_active', 1)->where('cryptobot_exchange_id', $cryptobot_exchange_id)->get();

        $cryptobot_currencies = Currency::doesntHave('strategy2')->pluck('id')->toArray();
        $groups = array();
        foreach ($cryptobotPairs as $pair) {
            if (in_array($pair->cryptobot_base_currency_id, $cryptobot_currencies)) {
                $groups[$pair->cryptobot_base_currency_id][$pair->cryptobot_quote_currency_id] = $pair;
            }
            if (in_array($pair->cryptobot_quote_currency_id, $cryptobot_currencies)) {
                $groups[$pair->cryptobot_quote_currency_id][$pair->cryptobot_base_currency_id] = $pair;
            }
        }

        foreach ($groups as $key => $group) {
            if (count($group) == 1) {
                foreach ($group as $row => $pair) {
                    unset($groups[$row][$key]);
                    unset($groups[$key]);
                }
            }
        }

        $cryptobotExchange = Exchange::find($cryptobot_exchange_id);
        $cryptobot_currencies = Currency::pluck('name', 'id')->toArray();
        // $cryptobot_pair_strategy = array();
        foreach ($groups as $key => $group) {
            $cryptobotStrategy = self::updateOrCreate([
                'name'        => strtoupper($cryptobotExchange->exchange) . "_{$cryptobot_currencies[$key]}",
            ], [
                'name'        => strtoupper($cryptobotExchange->exchange) . "_{$cryptobot_currencies[$key]}",
                'type'        => self::TYPE_CROSS_PAIR,
                'is_active'   => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
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
                        if (array_key_exists($index, $groups[$row])) {
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

            // DB::table('cryptobot_pair_strategy')->insert($cryptobot_pair_strategy);
            // DB::table('cryptobot_pair_strategy')->upsert($cryptobot_pair_strategy, ['cryptobot_pair_id', 'cryptobot_strategy_id'], ['is_base']);
        }

        return true;


        // $json_connection = json_encode($connection);
        // $log = 'unset';
        // Log::info(json_encode(compact('key', 'currency', 'json_connection', 'row', 'pair', 'log')));

        // $possible_connections = array();
        // $cryptobot_currencies = Currency::pluck('name', 'id')->toArray();
        // foreach ($cryptobot_currencies as $key => $currency) {
        //     $possible_connections[] = array('strategy' => $currency, 'pair' => array(), 'start' => $key, 'end' => $key, 'continue' => true);
        //     foreach ($possible_connections as $index => $connection) {
        //         if ($connection['continue'] && $key == $connection['end'] && array_key_exists($key, $groups)) {
        //             foreach ($groups[$key] as $row => $pair) {
        //                 if (!in_array($key, $a)) {
        //                     if ($row >= $key) {
        //                         $temp = $connection;
        //                         $temp['strategy'] .= "/{$cryptobot_currencies[$row]}";
        //                         $temp['pair'][] = $pair;
        //                         $temp['end'] = $row;
        //                         $possible_connections[] = $temp;
        //                     } elseif ($row == $connection['end'] && count($connection['pair']) > 2) {
        //                         $possible_connections[$index]['continue'] = false;
        //                     } else {
        //                         // unset($possible_connections[$index]);
        //                     }
        //                 } else {
        //                     if (in_array($row, $a)) {
        //                         if ($row >= $key) {
        //                             $temp = $connection;
        //                             $temp['strategy'] .= "/{$cryptobot_currencies[$row]}";
        //                             $temp['pair'][] = $pair;
        //                             $temp['end'] = $row;
        //                             $possible_connections[] = $temp;
        //                         } elseif ($row == $connection['end'] && count($connection['pair']) > 2) {
        //                             $temp['pair'][] = $pair;
        //                             $possible_connections[$index]['continue'] = false;
        //                         } else {
        //                             // unset($possible_connections[$index]);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
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
