<?php
namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_currencies';
    // protected $table = 'currencies';

    protected $guarded = [];

    public function quotePairs()
    {
        return $this->hasMany(Pair::class, 'cryptobot_quote_currency_id', 'id');
    }

    public function basePairs()
    {
        return $this->hasMany(Pair::class, 'cryptobot_base_currency_id', 'id');
    }

    public function strategy2()
    {
        return $this->hasOne(Strategy::class, 'cryptobot_currency_id', 'id');
    }

    public static function saveCurrencies($pairs, &$cryptobot_currencies)
    {
        $currencyIds = array(null, null);
        $currencies = explode('_', $pairs);
        if (array_key_exists(1, $currencies)) {
            if (array_key_exists($currencies[0], $cryptobot_currencies)) {
                $currencyIds[0] = $cryptobot_currencies[$currencies[0]];
            } else {
                $currencyIds[0] = Currency::updateOrCreate(['name' => $currencies[0]])->id;
            }
            $currencyIds[1] = null;
        } else {
            $currencies = explode('/', $pairs);
            if (array_key_exists(1, $currencies)) {
                foreach ($currencies as $index => $currency) {
                    if (array_key_exists($currency, $cryptobot_currencies)) {
                        $currencyIds[$index] = $cryptobot_currencies[$currency];
                    } else {
                        $currencyIds[$index] = Currency::updateOrCreate(['name' => $currency])->id;
                    }
                }
            }
        }

        return $currencyIds;
    }
}
