<?php

namespace App\Models\SealChamber;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'document_id', 'id');
    }
}
