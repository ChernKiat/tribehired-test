<?php

namespace App\Models\CryptoBot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PairStrategy extends Pivot
{
    // protected $connection = 'mysql';
    protected $table = 'cryptobot_pair_strategy';
    // protected $table = 'pair_strategy';

    public function pair()
    {
        return $this->belongsTo(Pair::class, 'cryptobot_pair_id', 'id');
    }
}

