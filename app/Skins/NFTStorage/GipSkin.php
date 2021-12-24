<?php

namespace App\Skins\NFTStorage;

use App\Models\CryptoBot\Exchange;
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

    public static function test($image, $directory)
    {
        $image = imagecreatefromjpeg("{$directory}\\{$image}");

        $red = imagecolorallocate($image, 255, 0, 0);

        imagecolortransparent($image, $red);

        imagefilledrectangle($image, 40, 40, 500, 25, $red);

        // header('Content-Type: image/png');

        imagepng($image, "{$directory}\\nice.png");
        imagedestroy($image);
        dd($image);
    }

    public function createOrder($type, $side, $amount, $price = null, $params = array())
    {
        if (!$this->passPreValidationsPreparations()) { throw new Exception('Please setup ccxt dependencies.'); }


        // return $cryptobotTrades;
        return $this;
    }
}
