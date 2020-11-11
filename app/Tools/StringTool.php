<?php

namespace App\Tools;

use Carbon\Carbon;
use Illuminate\Support\Str;

class StringTool
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

    public static function abbreviationsToIntegersConverter($abbreviation)
    {
        $map = array('k' => 1000, 'm' => 1000000, 'b' => 1000000000);
        list($value, $suffix) = sscanf(strtolower($abbreviation), "%f%s");
        return intval($value * $map[$suffix]);
    }
}
