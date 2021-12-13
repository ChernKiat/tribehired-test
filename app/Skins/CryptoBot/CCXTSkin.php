<?php

namespace App\Tools\CryptoBot;

use ccxt;
use App\Models\CryptoBot\Exchange;
use App\Models\CryptoBot\Pair;
use App\Models\CryptoBot\Ticker;

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

    public function fetchTickers($pairId, $exchange = null, $is_initiated = false)
    {
        if (!$is_initiated) {
            $this->initExchange($exchange);
        }

        $cryptobotPair = Pair::where('exchange', $exchange)->first();
        // try {
            Pair::updateOrCreate(['exchange_id' => $cryptobotExchange->id, 'exchange_pair' => $pair]);
            if ($ex->hasFetchTickers) {
                $tick = $class->fetchTicker($pair);
                unset($tick['info']);
                $dt = explode('.', $tick['datetime']);
                $tick['timestamp'] = (intval($tick['timestamp']) / 1000);
                $tick['bh_exchanges_id'] = $exid;
                $tick['datetime'] = $dt[0];

                $tick['basevolume'] = $tick['baseVolume'];
                unset($tick['baseVolume']);
                $tick['quotevolume'] = $tick['quoteVolume'];
                unset($tick['quoteVolume']);
                $tickers_model = new Models\BhTickers();

                $tickers_model::updateOrCreate(
                    ['bh_exchanges_id' => $exid, 'symbol' => $pair, 'timestamp' => $tick['timestamp']]
                    , $tick);
            }
        // } catch (ccxt\AuthenticationError $e) {
        //     return array('status' => false, 'status' => "\n\t{$exchange} needs auth (set this exchange to -1 in the database to disable it)..\n\n");
        // } catch (ccxt\BaseError $e) {
        //     return array('status' => false, 'status' => "\n\t{$exchange} error (set this exchange to -1 in the database to disable it):\n {$e}\n\n");
        // }
    }
}
