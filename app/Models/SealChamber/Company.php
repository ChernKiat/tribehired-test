<?php

namespace App\Models\SealChamber;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public function games()
    {
        return $this->hasMany(Game::class, 'company_id', 'id');
    }
}
