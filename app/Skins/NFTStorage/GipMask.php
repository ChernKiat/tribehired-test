<?php

namespace App\Skins\NFTStorage;

class GipMask
{
//    public $imageMaskFileName;
    public $imageMaskBinary;
    private $height, $width;

    public function __construct(GipImage $gipImage)
    {
        if ($gipImage) {
            $this->height = $gipImage->heightOrg;
            $this->width = $gipImage->widthOrg;
        }
    }

    public function createMask($colorBack, $colorFront, $shape)
    {
        //create img
        $this->imageMaskBinary = imagecreatetruecolor($this->width, $this->height);

        //wypełnienie całości w zależności od color
        imagefill($this->imageMaskBinary, 0, 0, $colorBack);

        //tworzenie elips
        for ($x = 0; $x < GipConfig::getInstance()->config['pettern']['shapes']; $x++) {
            imagefilledellipse($this->imageMaskBinary, $shape[$x][0], $shape[$x][1], $shape[$x][2], $shape[$x][2], $colorFront);
        }
    }

    public function setSize($height, $width)
    {
        $this->height = $height;
        $this->width = $width;
    }
}
