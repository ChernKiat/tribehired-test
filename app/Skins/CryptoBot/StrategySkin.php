<?php

namespace App\Skins\CryptoBot;

use App\Models\CryptoBot\Exchange;
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

class StrategySkin
{
    private $limit = null;
    private $since = null;

    private $exchange = null;

    public function __construct($exchange = null)
    {
        if (!is_null($exchange)) {
            $this->initExchange($exchange);
        }
        $this->limit = 5;
        $this->since = Carbon::now()->subMinutes(5)->timestamp * 1000;
    }

    public function setCryptobotExchange($cryptobotExchange)
    {
        $this->cryptobotExchange = $cryptobotExchange;

        return $this;
    }

    public function startGridDCABot($pair)
    {
        // try {
                foreach ($this->exchange->fetch_my_trades($this->cryptobotPair->pair, $this->since, $this->limit, $params) as $trade) {
                    $data = [];
                    $data['cryptobot_exchange_id']  = $this->cryptobotExchange->id;
                    $data['cryptobot_pair_id']      = $this->cryptobotPair->id;
                    $data['exchange_trade_id']      = $trade['id'];
                    $data['exchange_order_id']      = $trade['order'];
                    $data['timestamp']              = intval($trade['timestamp'] / 1000);
                    $data['datetime']               = Carbon::createFromTimestamp($data['timestamp'])->toDateTimeString();
                    $data['price']                  = $trade['price'];
                    $data['amount']                 = $trade['amount'];
                    $data['fee_cost']               = $trade['fee']['cost'] ?? null;
                    $data['fee_rate']               = $trade['fee']['rate'] ?? null;
                    $data['fee_currency']           = $trade['fee']['currency'] ?? null;
                    $data['is_own']                 = true;

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
                Log::info("{$this->exchange->id} doesnt have fetchMyTrades.");
        // } catch (Exception $e) {
        //     Log::error($e);
        // }

        // return $cryptobotTrades;
        return $this;
    }
}
