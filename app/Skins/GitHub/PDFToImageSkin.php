<?php

namespace App\Skins\GitHub;

use App\Tools\FolderTool;
use Spatie\PdfToImage\Pdf;

class PDFToImageSkin
{
    const PACKAGE_NAME  = 'pdf-to-image';

    public $image_addon; // postfix name of images extracted
    public $image_extension; // format of images extracted

    public $page = 0;

    public function __construct($imageAddon = '', $imageExtension = 'jpg') {
        $this->image_extension  = $imageExtension;
        $this->image_addon  = $imageAddon;
    }

    // save pdf to image into storage
    public function savePDFToImage($fullPath, $fileNameWithoutExtension, $extension)
    {
        $temp = new Pdf("{$fullPath}\\{$fileNameWithoutExtension}.{$extension}");
        $temp->setOutputFormat($this->image_extension);

        $this->page = $temp->getNumberOfPages();
        for ($i = 1; $i <= $this->page; $i++) {
            $temp->setPage($i)->saveImage($fullPath);

            FolderTool::renameAFile("{$fullPath}\\{$i}.{$this->image_extension}", "{$fullPath}\\{$fileNameWithoutExtension}-{$i}{$this->image_addon}.{$this->image_extension}");
        }
    }
}
