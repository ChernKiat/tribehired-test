<?php
namespace App\Models\SupportSystem;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'supportsystem_users';
    // protected $table = 'users';

    const GENDER_MALE      = 1;
    const GENDER_FEMALE    = 2;

    protected $fillable = [
    ];

}
