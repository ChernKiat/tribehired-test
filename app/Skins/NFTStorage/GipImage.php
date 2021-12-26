<?php

namespace App\Skins\NFTStorage;

class GipImage
{
    public $widthOrg;
    public $heightOrg;
    public $widthRescale, $heightRescale;
    public $fileName;
    public $fileNameShort;
    public $imageBinary;
    public $imageResized;

    public function __construct($fileName)
    {
        if (file_exists($fileName)) {
            $this->fileName = $fileName;

            $this->imageBinary = imagecreatefromstring(file_get_contents($this->fileName));
            $this->fileNameShort = pathinfo($this->fileName)['filename'];

            $this->widthOrg = imagesx($this->imageBinary);
            $this->heightOrg = imagesy($this->imageBinary);
        } else {
            GipConfig::getInstance()->addAlert('ALERT: No file to protect.');
        }
        return $this;
    }

    /**
     * @param $newWidth
     */
    public function rescaleWidth($newWidth, $oldHeight)
    {
        $newHeight = $this->heightOrg / ($this->widthOrg / $newWidth);
        if ($newHeight > $oldHeight) {
            $newWidth = $this->widthOrg / ($this->widthOrg / $newWidth);
            $newHeight = $this->heightOrg / ($this->widthOrg / $newWidth);
        }


        $imagePettern = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($imagePettern, $this->imageBinary, 0, 0, 0, 0, $newWidth, $newHeight, $this->widthOrg, $this->heightOrg);

        $this->imageResized = $imagePettern;
        $this->heightRescale = imagesy($this->imageResized);
        $this->widthRescale = imagesx($this->imageResized);
    }
}
