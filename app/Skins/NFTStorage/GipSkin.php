<?php

namespace App\Skins\NFTStorage;

use App\Models\CryptoBot\Exchange;
use App\Tools\ImageTool;
use Carbon\Carbon;
use Exception;
use Log;

class GipSkin
{
    private $limit = null;

    private $exchange = null;

    const TYPE_MARKET  = 'market';

    public function __construct($exchange = null)
    {
        if (!is_null($exchange)) {
            $this->initExchange($exchange);
        }
        $this->limit = 5;
    }

    public static function demo($image, $directory, $x = 2, $y = 2)
    {
        list($width, $height) = getimagesize("{$directory}\\{$image}");

        $min_x = floor($width / $x);
        $min_y = floor($height / $y);

        $array_x = array(0);
        $temp = 0;
        $balance = $width - ($min_x * $x);
        for ($i = 0; $i < $x; $i++) {
            $temp += $min_x;
            if ($i < $balance) {
                $temp++;
            }
            $array_x[] = $temp;
        }
        $array_y = array(0);
        $temp = 0;
        $balance = $height - ($min_y * $y);
        for ($i = 0; $i < $y; $i++) {
            $temp += $min_y;
            if ($i < $balance) {
                $temp++;
            }
            $array_y[] = $temp;
        }

        // $x = rand(0, $x - 1);
        // $y = rand(0, $y - 1);

        for ($i=0; $i < $x; $i++) {
            for ($j=0; $j < $y; $j++) {
                ImageTool::maskARectangle($image, $directory, $array_x[$i], $array_y[$j], $array_x[$i + 1], $array_y[$j + 1]);
            }
        }
    }
}
