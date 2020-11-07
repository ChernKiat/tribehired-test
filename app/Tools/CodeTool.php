<?php

namespace App\Tools;

use Carbon\Carbon;
use Illuminate\Support\Str;

class CodeTool
{
    public static function randomGenerator($type)
    {
        switch ($type) {
            case 'filename':
                return Str::random(10) . Carbon::now()->getTimestamp();
                break;
            default:
                return mt_rand(); // random integer
                break;
        }
    }
}
