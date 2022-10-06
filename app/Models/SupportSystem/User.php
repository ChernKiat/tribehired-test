<?php
namespace App\Models\SupportSystem;

use App\Tools\StringTool;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'supportsystem_users';
    // protected $table = 'users';

    const GENDER_MALE      = 1;
    const GENDER_FEMALE    = 2;
    const GENDER_OTHER     = 3;

    const GREETING_DESCRIPTION_LIST = array(
        1 => 'Happiness and prosperity',
        2 => 'Wishing you luck in the year of the Rabbit',
        3 => 'Happy Chinese New Year!',
        4 => 'Surplus & abundance year after year',
        5 => 'Selamat Tahun Cina :)',
        6 => '新年快乐, 恭喜发财',
        7 => '祝你新年快乐, 身体健康',
    );

    protected $fillable = [
    ];

    public function generateVoucherCode() {
        $this->voucher_code  = StringTool::randomGenerator('number', 6, false);
        $this->save();
    }
}
