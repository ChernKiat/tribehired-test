<?php

namespace App\Skins\CryptoBot;

use App\Models\CryptoBot\Currency;
use App\Models\CryptoBot\DynamicTicker;
use App\Models\CryptoBot\Exchange;
use App\Models\CryptoBot\Log AS CryptoLog;
use App\Models\CryptoBot\Ohclv;
use App\Models\CryptoBot\Order;
use App\Models\CryptoBot\Pair;
use App\Models\CryptoBot\Ticker;
use App\Models\CryptoBot\Trade;
use Carbon\Carbon;
use ccxt;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Log;

class CCXTSkin
{
    const TYPE_MARKET  = 'market';
    const TYPE_LIMIT   = 'limit';
    const TYPE_TAKER   = 'taker';
    const TYPE_MAKER   = 'maker';

    const SIDE_BUY   = 'buy';
    const SIDE_SELL  = 'sell';

    private $timeframe = '1m';
    private $since = null;
    private $mode = self::MODE_NORMAL;
    private $limit = null;

    private $exchange = null;

    // ------

    // private $is_multiple = false;

    const MODE_NORMAL   = 'normal';
    const MODE_DYNAMIC  = 'dynamic';
    const MODE_REVIVE   = 'revive';

    private $cryptobotExchange = null;
    private $cryptobotPair = null;

    public function __construct($parameters = array('exchange' => null, 'timeframe' => '1m', 'since' => null, 'mode' => self::MODE_NORMAL, 'limit' => null, )) {
        if (array_key_exists('exchange', $parameters) && !is_null($parameters['exchange'])) {
            $this->initExchange($parameters['exchange']);
        }
        $this->timeframe = $parameters['timeframe'] ?? '1m';
        $this->since = $parameters['since'] ?? Carbon::now()->subMinutes(5)->timestamp * 1000;
        $this->mode = $parameters['mode'] ?? self::MODE_NORMAL;
        $this->limit = $parameters['limit'] ?? 5;
        unset($parameters);
    }

    public function initExchange($exchange, $is_rate_limited = true)
    {
        $data = array(
            'apiKey'    => env(strtoupper($exchange) . '_APIKEY', null),
            'secret'    => env(strtoupper($exchange) . '_SECRET', null),
            'uid'       => env(strtoupper($exchange) . '_UID', null),
            'password'  => env(strtoupper($exchange) . '_PASSWORD', null),
        );
        if ($is_rate_limited) { $data['enableRateLimit'] = true; }

        $exchange = "ccxt\\{$exchange}";
        $this->exchange = new $exchange ($data);
        return $this->exchange; // for static (setup)
    }

    public function setCryptobotExchange($cryptobotExchange)
    {
        $this->cryptobotExchange = $cryptobotExchange;

        return $this;
    }

    public function setCryptobotPair($cryptobotPair)
    {
        // $this->is_multiple = ($cryptobotPair instanceof Collection);
        $this->cryptobotPair = $cryptobotPair;

        return $this;
    }

    private function passPreValidationsPreparations()
    {
        if (is_null($this->cryptobotExchange) || is_null($this->cryptobotPair)) {
            return false;
        }

        if (is_null($this->exchange)) {
            $this->initExchange($this->cryptobotExchange->exchange);
        }

        return true;
    }

    public function fetchTicker() // current market price summary
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        try {
            if ($this->exchange->has['fetchTicker']) {
                $data = $this->exchange->fetch_ticker($this->cryptobotPair->pair);
                foreach (['bid_volume' => 'bidVolume', 'ask_volume' => 'askVolume', 'base_volume' => 'baseVolume', 'quote_volume' => 'quoteVolume'] as $key => $value) {
                    if (array_key_exists($value, $data)) {
                        $data[$key] = $data[$value];
                        unset($data[$value]);
                    }
                }

                unset($data['symbol']);
                unset($data['previousClose']);
                unset($data['info']);
                $data['cryptobot_exchange_id'] = $this->cryptobotExchange->id;
                $data['cryptobot_pair_id'] = $this->cryptobotPair->id;
                $data['timestamp'] = intval($data['timestamp'] / 1000);
                $data['datetime'] = explode('.', $data['datetime'])[0];

                // $cryptobotTicker = Ticker::updateOrCreate([
                Ticker::updateOrCreate([
                    'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                    'cryptobot_pair_id'      => $data['cryptobot_pair_id'],
                    'timestamp'              => $data['timestamp']
                ], $data);
                unset($data);
            } else {
                Log::info("{$this->exchange->id} doesnt have fetchTicker.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Undefined index:')) {
                $this->cryptobotPair->is_active  = 0;
                $this->cryptobotPair->save();
                Log::error($e);
            } else {
                throw $e;
            }
        }

        // return $cryptobotTicker;
        return $this;
    }

    public function fetchTickers() // current markets price summary
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        // try {
            if ($this->exchange->has['fetchTickers']) {
                $data = $this->exchange->fetch_tickers($this->cryptobotPair->pluck('pair')->toArray());
                if ($this->mode != self::MODE_REVIVE) {
                    foreach ($this->cryptobotPair as $pair) {
                        if (!array_key_exists($pair->pair, $data)) {
                            Log::error("CCXTSkin > fetchTickers > {$this->exchange->id} > {$pair->pair} stopped");
                            $pair->is_active         = 0;
                            $pair->save();
                            // mail
                            continue;
                        }

                        foreach (['bid_volume' => 'bidVolume', 'ask_volume' => 'askVolume', 'base_volume' => 'baseVolume', 'quote_volume' => 'quoteVolume'] as $key => $value) {
                            if (array_key_exists($value, $data[$pair->pair])) {
                                $data[$pair->pair][$key] = $data[$pair->pair][$value];
                                unset($data[$pair->pair][$value]);
                            }
                        }

                        if ($data[$pair->pair]['bid'] == 0 || $data[$pair->pair]['bid_volume'] == 0 ||
                            $data[$pair->pair]['ask'] == 0 || $data[$pair->pair]['ask_volume'] == 0) {
                            Log::error("CCXTSkin > fetchTickers > {$this->exchange->id} > {$pair->pair} last ticked");
                            $pair->is_active         = 0;
                            $pair->latest_ticked_at  = explode('.', $data[$pair->pair]['datetime'])[0];
                            $pair->save();
                            unset($data[$pair->pair]);
                            // mail
                            continue;
                        }

                        unset($data[$pair->pair]['symbol']);
                        unset($data[$pair->pair]['previousClose']);
                        unset($data[$pair->pair]['info']);
                        $data[$pair->pair]['cryptobot_exchange_id'] = $this->cryptobotExchange->id;
                        $data[$pair->pair]['cryptobot_pair_id'] = $pair->id;
                        $data[$pair->pair]['timestamp'] = intval($data[$pair->pair]['timestamp'] / 1000);
                        $data[$pair->pair]['datetime'] = explode('.', $data[$pair->pair]['datetime'])[0];
                    }
                } else {
                    foreach ($this->cryptobotPair as $pair) {
                        $datetime = explode('.', $data[$pair->pair]['datetime'])[0];
                        // if ($pair->latest_ticked_at != $datetime) {
                            $pair->is_active         = 1;
                            $pair->save();
                            // mail
                            continue;
                        // }
                    }
                }
                unset($pair);
                if ($this->mode == self::MODE_DYNAMIC) {
                    foreach ($data as $value) {
                        // $cryptobotTickers[] = Ticker::updateOrCreate([
                        DynamicTicker::createDynamicTable($value['timestamp'])->updateOrCreate([
                            'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                            'cryptobot_pair_id'      => $value['cryptobot_pair_id'],
                            'timestamp'              => $value['timestamp']
                        ], $value);
                    }
                } elseif ($this->mode == self::MODE_REVIVE) {
                } else {
                    foreach ($data as $value) {
                        // $cryptobotTickers[] = Ticker::updateOrCreate([
                        Ticker::updateOrCreate([
                            'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                            'cryptobot_pair_id'      => $value['cryptobot_pair_id']
                        ], $value);
                    }
                }
                unset($value);
                unset($data);
            } else {
                Log::info("{$this->exchange->id} doesnt have fetchTickers.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotTickers;
        return $this;
    }

    public function fetchOHLCV($params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        try {
            if ($this->exchange->has['fetchOHLCV']) {
                foreach ($this->exchange->fetch_ohlcv($this->cryptobotPair->pair, $this->timeframe, $this->since, $this->limit, $params) as $ohlcv) {
                    $data = [];
                    $data['cryptobot_exchange_id']  = $this->cryptobotExchange->id;
                    $data['cryptobot_pair_id']      = $this->cryptobotPair->id;
                    $data['timestamp']              = intval($ohlcv[0] / 1000);
                    $data['datetime']               = Carbon::createFromTimestamp($data['timestamp'])->toDateTimeString();
                    $data['open']                   = $ohlcv[1];
                    $data['high']                   = $ohlcv[2];
                    $data['low']                    = $ohlcv[3];
                    $data['close']                  = $ohlcv[4];
                    $data['volume']                 = $ohlcv[5];

                    // $cryptobotOhclv[] = Ohclv::updateOrCreate([
                    Ohclv::updateOrCreate([
                        'cryptobot_exchange_id'  => $data['cryptobot_exchange_id'],
                        'cryptobot_pair_id'      => $data['cryptobot_pair_id'],
                        'timestamp'              => $data['timestamp']
                    ], $data);
                }
                unset($ohlcv);
                unset($params);
                unset($data);
            } else {
                Log::info("{$this->exchange->id} doesnt have fetchOHLCV.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Undefined index:')) {
                $this->cryptobotPair->is_active  = 0;
                $this->cryptobotPair->save();
                Log::error($e);
            } else {
                throw $e;
            }
        }

        // return $cryptobotOhclv;
        return $this;
    }

    public function fetchTrades($params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        // try {
            if ($this->exchange->has['fetchTrades']) {
                foreach ($this->exchange->fetch_trades($this->cryptobotPair->pair, $this->since, $this->limit, $params) as $trade) {
                    $data = [];
                    $data['cryptobot_exchange_id']  = $this->cryptobotExchange->id;
                    $data['cryptobot_pair_id']      = $this->cryptobotPair->id;
                    $data['exchange_trade_id']      = $trade['id'];
                    $data['exchange_order_id']      = $trade['order'];
                    $data['timestamp']              = intval($trade['timestamp'] / 1000);
                    $data['datetime']               = Carbon::createFromTimestamp($data['timestamp'])->toDateTimeString();
                    if (!empty($trade['type']) && in_array($trade['type'], array(self::TYPE_MARKET, self::TYPE_LIMIT))) {
                        $data['type']               = $trade['type'];
                    } else {
                        $data['type']               = (!empty($trade['takerOrMaker']) && in_array($trade['takerOrMaker'],
                                                            array(self::TYPE_TAKER, self::TYPE_MAKER))) ? $trade['takerOrMaker'] :
                                                                null;
                    }
                    $data['side']                   = (!empty($trade['side']) && in_array($trade['side'],
                                                            array(self::SIDE_BUY, self::SIDE_BUY))) ? $trade['side'] : null;
                    $data['price']                  = $trade['price'];
                    $data['amount']                 = $trade['amount'];
                    $data['fee_cost']               = $trade['fee']['cost'] ?? null;
                    $data['fee_rate']               = $trade['fee']['rate'] ?? null;
                    $data['fee_currency']           = $trade['fee']['currency'] ?? null;
                    // $trade['fees'] = an array with a list of fees I think

                    // $cryptobotTrades[] = Trade::updateOrCreate([
                    Trade::updateOrCreate([
                        'cryptobot_exchange_id'  => $data['cryptobot_exchange_id'],
                        'cryptobot_pair_id'      => $data['cryptobot_pair_id'],
                        'exchange_trade_id'      => $data['exchange_trade_id'],
                        'exchange_order_id'      => $data['exchange_order_id']
                    ], $data);
                }
                unset($trade);
                unset($params);
                unset($data);
            } else {
                Log::info("{$this->exchange->id} doesnt have fetchTrades.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotTrades;
        return $this;
    }

    public function fetchMyTrades($params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        // try {
            if ($this->exchange->has['fetchMyTrades']) {
                foreach ($this->exchange->fetch_my_trades($this->cryptobotPair->pair, $this->since, $this->limit, $params) as $trade) {
                    $data = [];
                    $data['cryptobot_exchange_id']  = $this->cryptobotExchange->id;
                    $data['cryptobot_pair_id']      = $this->cryptobotPair->id;
                    $data['exchange_trade_id']      = $trade['id'];
                    $data['exchange_order_id']      = $trade['order'];
                    $data['timestamp']              = intval($trade['timestamp'] / 1000);
                    $data['datetime']               = Carbon::createFromTimestamp($data['timestamp'])->toDateTimeString();
                    if (!empty($trade['type']) && in_array($trade['type'], array(self::TYPE_MARKET, self::TYPE_LIMIT))) {
                        $data['type']               = $trade['type'];
                    } else {
                        $data['type']               = (!empty($trade['takerOrMaker']) && in_array($trade['takerOrMaker'],
                                                            array(self::TYPE_TAKER, self::TYPE_MAKER))) ? $trade['takerOrMaker'] :
                                                                null;
                    }
                    $data['side']                   = (!empty($trade['side']) && in_array($trade['side'],
                                                            array(self::SIDE_BUY, self::SIDE_BUY))) ? $trade['side'] : null;
                    $data['price']                  = $trade['price'];
                    $data['amount']                 = $trade['amount'];
                    $data['fee_cost']               = $trade['fee']['cost'] ?? null;
                    $data['fee_rate']               = $trade['fee']['rate'] ?? null;
                    $data['fee_currency']           = $trade['fee']['currency'] ?? null;
                    $data['is_own']                 = true;
                    // $trade['fees'] = an array with a list of fees I think

                    // $cryptobotTrades[] = Trade::updateOrCreate([
                    Trade::updateOrCreate([
                        'cryptobot_exchange_id'  => $data['cryptobot_exchange_id'],
                        'cryptobot_pair_id'      => $data['cryptobot_pair_id'],
                        'exchange_trade_id'      => $data['exchange_trade_id'],
                        'exchange_order_id'      => $data['exchange_order_id']
                    ], $data);
                }
                unset($trade);
                unset($params);
                unset($data);
            } else {
                Log::info("{$this->exchange->id} doesnt have fetchMyTrades.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotTrades;
        return $this;
    }

    public function fetchBalance($params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        // try {
            if ($this->exchange->has['fetchBalance']) {
                $data = $this->exchange->fetch_balance($params);
                $balances = [];
                foreach ($this->cryptobotPair->pluck('pair')->toArray() as $pair) {
                    $balances[$pair]['free']        = $data[$pair]['free'];
                    $balances[$pair]['used']        = $data[$pair]['used'];
                }
                unset($params);
                unset($data);
                unset($pair);
            } else {
                Log::info("{$this->exchange->id} doesnt have fetchBalance.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotBalances;
        // return $this;
        return $balances;
    }

    // public function createMarketBuyOrder($amount, $price = null, $params = array())
    // public function createMarketSellOrder($amount, $price = null, $params = array())
    // public function createLimitBuyOrder($amount, $price = null, $params = array())
    // public function createLimitSellOrder($amount, $price = null, $params = array())

    public function createOrder($type, $side, $amount, $price = null, $params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        // try {
            if ($this->exchange->has['createOrder']) {
                $cryptobotOrder = Order::updateOrCreate([
                    'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                    'cryptobot_pair_id'      => $this->cryptobotPair->id,
                    'exchange_trade_id'      => null,
                    'exchange_order_id'      => null
                ], [
                    'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                    'cryptobot_pair_id'      => $this->cryptobotPair->id,
                    'exchange_trade_id'      => null,
                    'exchange_order_id'      => null,
                    'created_at'             => null,
                    'updated_at'             => null
                ]);
                $params['clientOrderId']  = $cryptobotOrder->id;

                $order = $this->exchange->create_order($this->cryptobotPair->pair, $type, $side, $amount, $price, $params);
                // $cryptobotOrder->exchange_trade_id  = null;
                $cryptobotOrder->exchange_order_id  = $order['id'];
                $cryptobotOrder->timestamp          = intval($order['timestamp'] / 1000);
                $cryptobotOrder->datetime           = Carbon::createFromTimestamp($cryptobotOrder->timestamp)->toDateTimeString();
                if (!empty($order['type']) && in_array($order['type'], array(self::TYPE_MARKET, self::TYPE_LIMIT))) {
                    $cryptobotOrder->type           = $order['type'];
                } else {
                    $cryptobotOrder->type           = (!empty($order['takerOrMaker']) && in_array($order['takerOrMaker'],
                                                        array(self::TYPE_TAKER, self::TYPE_MAKER))) ? $order['takerOrMaker'] :
                                                            null;
                }
                $cryptobotOrder->side               = (!empty($order['side']) && in_array($order['side'],
                                                        array(self::SIDE_BUY, self::SIDE_BUY))) ? $order['side'] : null;
                $cryptobotOrder->price              = $order['price'];
                $cryptobotOrder->amount             = $order['amount'];
                $cryptobotOrder->fee_cost           = $order['fee']['cost'] ?? null;
                $cryptobotOrder->fee_rate           = $order['fee']['rate'] ?? null;
                $cryptobotOrder->fee_currency       = $order['fee']['currency'] ?? null;
                $cryptobotOrder->is_own             = true;
                $cryptobotOrder->created_at         = Carbon::now();
                $cryptobotOrder->save();
                // $order['fees'] = an array with a list of fees I think
                unset($order);
                unset($cryptobotOrder);
            } else {
                Log::info("{$this->exchange->id} doesnt have createOrder.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotOrder;
        return $this;
    }

    public function cancelOrder($order, $params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }

        // try {
            if ($this->exchange->has['cancelOrder']) {
                if (is_object($order)) {
                    $exchange_order_id = $order->exchange_order_id;
                    $order = $params['clientOrderId'] = $order->id;
                } else {
                    $exchange_order_id = $params['clientOrderId'] = $order;
                }

                $this->exchange->cancel_order($exchange_order_id, $this->cryptobotPair->pair, $params);

                $cryptobotOrder = Order::updateOrCreate([
                    'id'           => $order,
                ], [
                    'canceled_at'  => Carbon::now(),
                ]);

                // $order['fees'] = an array with a list of fees I think
                unset($exchange_order_id);
                unset($order);
                unset($cryptobotOrder);
            } else {
                Log::info("{$this->exchange->id} doesnt have cancelOrder.");
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     Log::error("{$this->exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
        //     return false;
        // } catch (ccxt\BaseError $e) {
        //     Log::error("{$this->exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
        //     return false;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotOrder;
        return $this;
    }

    public static function updateExchanges()
    {
        // delete old
        foreach (array_diff(Exchange::pluck('exchange')->toArray(), ccxt\Exchange::$exchanges) as $exchange) {
            $exchange = Exchange::where('exchange', $exchange)->first();
            $exchange->delete();
        }

        foreach (ccxt\Exchange::$exchanges as $exchange) {
            // try {
                $exchange = (new self())->initExchange($exchange);
                $urls = $exchange->urls;
                $data = array();
                $data['exchange'] = $exchange->id;
                // encode all APIs & documentation URLs
                $data['data'] = json_encode($exchange->api, 1) . json_encode($urls, 1);
                $data['url'] = !is_array($urls['www']) ? $urls['www'] : $urls['www'][0];
                $data['url_api'] = !is_array($urls['api']) ? $urls['api'] :
                                    (!is_array($urls['api'] = array_shift($urls['api'])) ? $urls['api'] : array_shift($urls['api']));
                $data['url_doc'] = !is_array($urls['doc']) ? $urls['doc'] : $urls['doc'][0];

                Exchange::updateOrCreate(['exchange' => $exchange->id], $data);
            // } catch (ccxt\AuthenticationError $e) {
            //     Log::error("{$exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
            //     return false;
            // } catch (ccxt\BaseError $e) {
            //     Log::error("{$exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
            //     return false;
            // } catch (Exception $e) {
            //     Log::error($e);
            // }
        }
    }

    public static function updatePairs()
    {
        $cryptobot_currencies = Currency::pluck('name', 'id')->toArray();

        foreach (Exchange::with('pairs')->where('is_active', 1)->get() as $exchange) {
            $cryptobotPairs = array_column($exchange->pairs->all(), null, 'pair');
            $pairs = (new self())->initExchange($exchange->exchange)->load_markets();
            if (empty($pairs)) {
                Log::info("{$exchange->exchange} pairs variable is empty.");
                continue;
            }

            foreach ($pairs as $key => $pair) {
                // try {
                    if (array_key_exists($key, $cryptobotPairs)) {
                        $currencies = array(null, null);
                        $cryptobotPair = $cryptobotPairs[$key];
                        if (is_null($cryptobotPair->cryptobot_quote_currency_id)) {
                            $currencies = Currency::saveCurrencies($key, $cryptobot_currencies);
                        }
                        $cryptobotPair->cryptobot_base_currency_id = $currencies[0];
                        if (!is_null($currencies[1])) { $cryptobotPair->cryptobot_quote_currency_id = $currencies[1]; }
                        $cryptobotPair->save();
                    } else {
                        $currencies = Currency::saveCurrencies($key, $cryptobot_currencies);
                        $cryptobotPair = Pair::updateOrCreate(['cryptobot_exchange_id' => $exchange->id, 'cryptobot_quote_currency_id' => $currencies[1], 'cryptobot_base_currency_id' => $currencies[0], 'pair' => $key, 'is_active' => $pair['active']]);
                    }
                // } catch (ccxt\AuthenticationError $e) {
                //     Log::error("{$exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
                //     return false;
                // } catch (ccxt\BaseError $e) {
                //     Log::error("{$exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
                //     return false;
                // } catch (Exception $e) {
                //     Log::error($e);
                // }
            }
        }
    }

    // deprecated the cryptobotPair table got new columns
    public static function setupPairs()
    {
        // no delete

        foreach(ccxt\Exchange::$exchanges as $exchange) {
            $cryptobotExchange = Exchange::doesntHave('pairs')->where('exchange', $exchange)->first();
            // aofex ExchangeNotAvailable (525 Origin SSL Handshake Error)
            // bibox ExchangeError
            // buda ExchangeNotAvailable (DDoS protection by Cloudflare - 6bef9890ce49efb6)
            // coinbase empty
            // coinmarketcap ExchangeNotAvailable (DDoS protection by Cloudflare - 6befa1766af0206b)
            // crex24 53 timeout
            // flowbtc ExchangeNotAvailable (Missing Authentication Token)
            // lykke ExchangeNotAvailable (1005)
            // ripio ExchangeNotAvailable (1020)
            if (empty($cryptobotExchange) || in_array($exchange, ['aofex', 'bibox', 'buda', 'coinbase', 'coinmarketcap', 'crex24', 'flowbtc', 'lykke', 'ripio'])) { continue; }

            $cryptobotExchange = Exchange::where('exchange', $exchange->id)->first();
            $exchange = (new self())->initExchange($exchange);
            $pairs = $exchange->load_markets();
            if (empty($pairs) || empty($cryptobotExchange)) {
                Log::info("{$exchange->id} pairs @ cryptobotExchange variable is empty.");
                continue;
            }

            foreach (array_keys($pairs) as $pair) {
                // try {
                    Pair::updateOrCreate(['cryptobot_exchange_id' => $cryptobotExchange->id, 'pair' => $pair]);
                // } catch (ccxt\AuthenticationError $e) {
                //     Log::error("{$exchange->id} needs auth (set this exchange to -1 in the database to disable it)..");
                //     return false;
                // } catch (ccxt\BaseError $e) {
                //     Log::error("{$exchange->id} error (set this exchange to -1 in the database to disable it):\n{$e}");
                //     return false;
                // } catch (Exception $e) {
                //     Log::error($e);
                // }
            }
        }
    }
}
