<?php

namespace App\Tools\CryptoBot;

use App\Models\CryptoBot\Exchange;
use App\Models\CryptoBot\Ohclv;
use App\Models\CryptoBot\Pair;
use App\Models\CryptoBot\Ticker;
use Carbon\Carbon;
use ccxt;

class CCXTSkin
{
    private $exchange = null;
    private $cryptobotExchange = null;
    private $cryptobotPair = null;

    public static $exchanges = ccxt\Exchange::$exchanges;

    public function initExchange($exchange)
    {
        $exchange = "\ccxt\\{$exchange}";
        $this->exchange = new $exchange (array(
            'apiKey'    => env(strtoupper($exchange) . '_APIKEY', null),
            'secret'    => env(strtoupper($exchange) . '_SECRET', null),
            'uid'       => env(strtoupper($exchange) . '_UID', null),
            'password'  => env(strtoupper($exchange) . '_PASSWORD', null),
        ));
    }

    public function setCryptobotExchange($cryptobotExchange)
    {
        $this->cryptobotExchange = $cryptobotExchange;
    }

    public function setCryptobotPair($cryptobotPair)
    {
        $this->cryptobotPair = $cryptobotPair;
    }

    public function updateExchanges($is_initiated = false)
    {
        // no delete

        foreach (self::$exchanges as $exchange) {
            // try {
                if (!$is_initiated) {
                    $this->initExchange($exchange);
                }

                $urls = $this->exchange->urls;
                $data = array();
                $data['exchange'] = $exchange;
                $data['has_fetch_tickers'] = $this->exchange->hasFetchTickers ?? 1;
                $data['has_fetch_ohlcv'] = $this->exchange->hasFetchOHLCV ?? 1;
                // encode all APIs & documentation URLs
                $data['data'] = json_encode($this->exchange->api, 1) . json_encode($urls, 1);
                $data['url'] = is_array($urls['www']) ? $urls['www'][0] : $urls['www'];
                $data['url_api'] = is_array($urls['api']) ? array_shift($urls['api']) : $urls['api'];
                $data['url_doc'] = is_array($urls['doc']) ? $urls['doc'][0] : $urls['doc'];

                Exchange::updateOrCreate(['exchange' => $exchange], $data);
            // } catch (ccxt\AuthenticationError $e) {
                // return array('status' => false, 'status' => "\n\t{$exchange} needs auth (set this exchange to -1 in the database to disable it)..\n\n");
            // } catch (ccxt\BaseError $e) {
                // return array('status' => false, 'status' => "\n\t{$exchange} error (set this exchange to -1 in the database to disable it):\n {$e}\n\n");
            // }
        }
        // return array('status' => true);
        return true;
    }

    public function updatePairs($is_initiated = false)
    {
        // no delete

        foreach(self::$exchanges as $exchange) {
            if (!$is_initiated) {
                $this->initExchange($exchange);
            }

            $pairs = $this->exchange->load_markets();
            if (empty($pairs)) {
                continue;
            }

            $this->cryptobotExchange = Exchange::where('exchange', $exchange)->first();
            foreach (array_keys($pairs) as $pair) {
                // try {
                    Pair::updateOrCreate(['exchange_id' => $this->cryptobotExchange->id, 'exchange_pair' => $pair]);
                // } catch (ccxt\AuthenticationError $e) {
                //     return array('status' => false, 'status' => "\n\t{$exchange} needs auth (set this exchange to -1 in the database to disable it)..\n\n");
                // } catch (ccxt\BaseError $e) {
                //     return array('status' => false, 'status' => "\n\t{$exchange} error (set this exchange to -1 in the database to disable it):\n {$e}\n\n");
                // }
            }
        }
    }

    public function fetchTickers($pair = null, $exchange = null, $is_initiated = false)
    {
        if (!$is_initiated) {
            $this->initExchange($exchange);
        }

        if (is_null($this->cryptobotExchange)) {
            if (!is_null($exchange)) {
                $this->setCryptobotExchange(!is_object($exchange) ? Exchange::find($exchange) : $exchange);
            } else {
                return false;
            }
        }

        if (is_null($this->cryptobotPair)) {
            if (!is_null($pair)) {
                $this->setCryptobotPair(!is_object($pair) ? Pair::find($pair) : $pair);
            } else {
                return false;
            }
        }

        // try {
            if ($this->cryptobotExchange->hasFetchTickers) {
                $data = $this->exchange->fetchTicker($pair);
                unset($data['info']);
                $data['cryptobot_exchange_id'] = $this->cryptobotExchange->id;
                $data['cryptobot_pair_id'] = $this->cryptobotPair->id;
                $data['timestamp'] = (intval($data['timestamp']) / 1000);
                $data['datetime'] = explode('.', $data['datetime'])[0];

                $data['bid_volume'] = $data['bidVolume'];
                unset($data['bidVolume']);
                $data['ask_volume'] = $data['askVolume'];
                unset($data['askVolume']);
                $data['base_volume'] = $data['baseVolume'];
                unset($data['baseVolume']);
                $data['quote_volume'] = $data['quoteVolume'];
                unset($data['quoteVolume']);

                Ticker::updateOrCreate([
                    'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                    'cryptobot_pair_id'      => $this->cryptobotPair->id,
                    'timestamp'              => $data['timestamp']
                ], $data);
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     return array('status' => false, 'status' => "\n\t{$exchange} needs auth (set this exchange to -1 in the database to disable it)..\n\n");
        // } catch (ccxt\BaseError $e) {
        //     return array('status' => false, 'status' => "\n\t{$exchange} error (set this exchange to -1 in the database to disable it):\n {$e}\n\n");
        // }
    }

    public function fetchOHLCVs($pair = null, $exchange = null, $is_initiated = false)
    {
        if (!$is_initiated) {
            $this->initExchange($exchange);
        }

        if (is_null($this->cryptobotExchange)) {
            if (!is_null($exchange)) {
                $this->setCryptobotExchange(!is_object($exchange) ? Exchange::find($exchange) : $exchange);
            } else {
                return false;
            }
        }

        if (is_null($this->cryptobotPair)) {
            if (!is_null($pair)) {
                $this->setCryptobotPair(!is_object($pair) ? Pair::find($pair) : $pair);
            } else {
                return false;
            }
        }

        // try {
            if ($this->cryptobotPair->has_fetch_ohlcv) {
                foreach ($this->exchange->fetchOHLCV($this->cryptobotPair->pair, '1m', (Carbon::now()->subMinutes(5)->timestamp * 1000), 5) as $ohlcv) {
                    $data = [];
                    $data['cryptobot_exchange_id']  = $this->cryptobotExchange->id;
                    $data['cryptobot_pair_id']      = $this->cryptobotPair->id;
                    $data['timestamp']              = (intval($ohlcv[0]) / 1000);
                    $data['datetime']               = Carbon::createFromTimestamp($data['timestamp'])->toDateTimeString();
                    $data['open']                   = $ohlcv[1];
                    $data['high']                   = $ohlcv[2];
                    $data['low']                    = $ohlcv[3];
                    $data['close']                  = $ohlcv[4];
                    $data['volume']                 = $ohlcv[5];

                    Ohclv::updateOrCreate([
                        'cryptobot_exchange_id'  => $this->cryptobotExchange->id,
                        'cryptobot_pair_id'      => $this->cryptobotPair->id,
                        'timestamp'              => $data['timestamp']
                    ], $data);
                }
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     return array('status' => false, 'status' => "\n\t{$exchange} needs auth (set this exchange to -1 in the database to disable it)..\n\n");
        // } catch (ccxt\BaseError $e) {
        //     return array('status' => false, 'status' => "\n\t{$exchange} error (set this exchange to -1 in the database to disable it):\n {$e}\n\n");
        // }
    }
}
