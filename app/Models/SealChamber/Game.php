<?php

namespace App\Models\SealChamber;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';

    public function company()
    {
        return $this->belongsTo(Company::class, 'document_id', 'id');
    }
}
