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

// ------

use Log;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;

class PayPalSkin
{
    const MODE_SANDBOX  = 'sandbox';

    private $mode = self::MODE_NORMAL;

    // ------

    const TYPE_MARKET  = 'market';
    const TYPE_LIMIT   = 'limit';
    const TYPE_TAKER   = 'taker';
    const TYPE_MAKER   = 'maker';

    const SIDE_BUY   = 'buy';
    const SIDE_SELL  = 'sell';

    private $timeframe = '1m';
    private $since = null;
    private $limit = null;

    private $exchange = null;


    // private $is_multiple = false;


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
                    if (array_key_exists($value, $data[$pair->pair])) {
                        $data[$pair->pair][$key] = $data[$pair->pair][$value];
                        unset($data[$pair->pair][$value]);
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

        $httpClient = new HttpClient(['verify' => false]);

        $httpBaseUrl = 'https://'.(getSetting('PAYPAL_MODE') == 'sandbox' ? 'api-m.sandbox' : 'api-m').'.paypal.com/';

        // Attempt to retrieve the auth token
        try {
            $payPalAuthRequest = $httpClient->request('POST', $httpBaseUrl . 'v1/oauth2/token', [
                    'auth' => [getSetting('PAYPAL_CLIENT_ID'), getSetting('PAYPAL_SECRET')],
                    'form_params' => [
                        'grant_type' => 'client_credentials'
                    ]
                ]
            );

            $payPalAuth = json_decode($payPalAuthRequest->getBody()->getContents());
        } catch (BadResponseException $e) {
            Log::info($e->getResponse()->getBody()->getContents());
            return response()->json(['status' => 400], 400);
        }

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
                        $cryptobotPair = $cryptobotPairs[$key];
                    } else {
                        $currencies = explode('/', $key);
                        foreach ($currencies as $index => $currency) {
                            if (array_key_exists($currency, $cryptobot_currencies)) {
                                $currencies[$index] = $cryptobot_currencies[$currency];
                            } else {
                                $currencies[$index] = Currency::updateOrCreate(['name' => $currency])->id;
                            }
                        }
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

            $exchange = (new self())->initExchange($exchange);
            $pairs = $exchange->load_markets();
            $cryptobotExchange = Exchange::where('exchange', $exchange->id)->first();
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
