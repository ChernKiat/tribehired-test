<?php

namespace App\Links;

use GuzzleHttp\Client;

class BaseLink
{
    const BASE_URL  = "";

    public static function accessEndpoint()
    {
        return new Client(['base_uri' => static::BASE_URL]);
    }
}
