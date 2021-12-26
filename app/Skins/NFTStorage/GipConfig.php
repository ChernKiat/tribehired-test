<?php

namespace App\Skins\NFTStorage;

class GipConfig
{
    public $config = [
        'pettern' => [
            'shapes' => 10,
            'minShapeOfImage' => 7,//maxImageSize/minShapeOfImage image 300 => 300/10 = 30
            'maxShapeOfImage' => 3,//maxImageSize/maxShapeOfImage image 300 => 300/3 = 100
        ],
        'dirImage' => 'picture',// katalog dla zdjęć
    ];
    private static $instance;
    private $alerts = [];

    private function __construct()
    {

    }

    public function addAlert($alert)
    {
        array_push($this->alerts, $alert);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new GipConfig();
        }
        return self::$instance;
    }

    public function showAlerts()
    {
        $ret = '';
        foreach ($this->alerts as $key => $value) {
            $ret = $ret . $key . ' -> ' . $value . '<br>';
        }
        return $ret;
    }

    private function __clone()
    {
    }

}
