<?php

namespace App\Tools;

use Carbon\Carbon;
use Illuminate\Support\Str;

class StringTool
{
    public static function randomGenerator($type, $length = 8, $safe_mode = true)
    {
        switch ($type) {
            case 'filename':
                return Str::random(10) . Carbon::now()->getTimestamp();
                break;
            case 'letter':
                if ($safe_mode) {
                    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // no I & O
                } else {
                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                }
                break;
            case 'number':
                if ($safe_mode) {
                    $characters = '23456789'; // no 0 & 1
                } else {
                    $characters = '0123456789';
                }
                break;
            case 'both':
                if ($safe_mode) {
                    $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
                } else {
                    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                }
                break;
            default:
                return mt_rand(); // random integer
                break;
        }
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function abbreviationsToIntegersConverter($abbreviation)
    {
        $map = array('k' => 1000, 'm' => 1000000, 'b' => 1000000000);
        list($value, $suffix) = sscanf(strtolower($abbreviation), "%f%s");

        $output = is_null($suffix) ? $value : $value * $map[$suffix];
        return intval($output);
    }
}
