<?php

namespace App\Skins\NFTStorage;

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
}
