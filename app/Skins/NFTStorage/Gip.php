<?php

namespace App\Skins\NFTStorage;

class Gip
{
    public $imageToProtectFileName;
    private $gipImage;
    private $divWidth = null, $divHeight = null;

    public function __construct($imgToProtectFileName)
    {
        if (file_exists($imgToProtectFileName)) {
            $this->imageToProtectFileName = $imgToProtectFileName;

        } else {
            GipConfig::getInstance()->addAlert('ALERT: No file to protect.');
            $this->imageToProtectFileName = null;
        }
    }

    public function createProtectImageResize($newWidth, $newHeight, GipMask $mask, GipImage $gipImage)
    {
        $this->gipImage = $gipImage;

        // usatwienie nowych wymiarów dla obiektu
        $this->divWidth = $newWidth;
        $this->divHeight = $newHeight;

        //określenie jaki kolor jest przezroczysty w masce
        imagecolortransparent($mask->imageMaskBinary, imagecreatetruecolor(400, 500));

        //połączenie maski i zdjęcia
        imagecopymerge($this->gipImage->imageResized, $mask->imageMaskBinary, 0, 0, 0, 0);

        //ustawienie przezroczystości na zdjęciu wynikowym
        imagecolortransparent($this->gipImage->imageResized, imagecreatetruecolor(400, 500));

        //zapis do png
        imagepng($this->gipImage->imageResized, public_path('myNFTStorage\a.jpg'));
    }

    public function shapePositionArray($withMax, $heightMax)
    {
        $conf = GipConfig::getInstance()->config;
        $shapePos = [];
        for ($x = 0; $x < $conf['pettern']['shapes']; $x++) {
            $xPos = rand(0, $withMax);
            $yPos = rand(0, $heightMax);
            $sizePos = rand($withMax / $conf['pettern']['minShapeOfImage'], $withMax / $conf['pettern']['maxShapeOfImage']);
            array_push($shapePos, array($xPos, $yPos, $sizePos));
        }

        return $shapePos;
    }
}
