<?php

namespace App\Http\Requests\CryptoBot;

use App\Models\CryptoBot\Exchange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OHLCVRequest extends FormRequest
{
    public function authorize()
    {
        return true; // permissions checking
    }

    public function rules()
    {
        return [
            'pair'  => [
                'required',
                Rule::exists('cryptobot_pairs')->where(function ($query) {
                    return $query->where('cryptobot_exchange_id', Exchange::BINANCE);
                })
            ],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
