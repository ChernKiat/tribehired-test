<?php

namespace App\Skins\GitHub;

use App\Tools\PythonTool;

class PDFMinerSixSkin
{
    const PACKAGE_NAME            = 'pdfminer.six';
    const LOCAL_SCRIPT_FULL_PATH  = '"C:\Python38\Scripts\pdf2txt.py"';

    public function findText($fullPath, $fileName, $isThrowable = true)
    {
        return PythonTool::run("python " . self::LOCAL_SCRIPT_FULL_PATH . " \"{$fullPath}\\{$fileName}\"", $isThrowable);
    }
}
