<?php

namespace App\Tools;

use GuzzleHttp\Client;

class APITool
{
    const BASE_URL  = "";

    public static function accessEndpoint()
    {
        return new Client(['base_uri' => static::BASE_URL]);
    }
}
