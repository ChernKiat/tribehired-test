<?php

namespace App\Skins\GitHub;

use App\Tools\PythonTool;

class PDFMinerSkin
{
    const PACKAGE_NAME            = 'pdfminer';
    const LOCAL_SCRIPT_FULL_PATH  = '"C:\Anaconda3\Scripts\pdf2txt.py"';

    const LANGUAGE_CHINESE_MANDARIN_ENCODING  = 'euc-cn';
    const LANGUAGE_JAPANESE_ENCODING          = 'euc-jp';
    const LANGUAGE_KOREAN_ENCODING            = 'euc-kr';

    private $password = "";
    public $encoding_format = "";
    public $output_filename = "";
    public $layout_analysis_include_figures = false;
    public $vertical_writing_detection = false; // for CJK only (chinese, japanese, korean)

    public function findText($fullPath, $fileName, $isThrowable = true)
    {
        $passwordPDF = "";
        if (!empty($this->password)) {
            $passwordPDF = " -P \"{$this->password}\"";
        }

        $outputFilepath = "";
        if (!empty($this->output_filename)) {
            $outputFilepath = " -o \"{$fullPath}\\{$this->output_filename}\"";
        }

        $encodingFormat = "";
        if (!empty($this->encoding_format)) {
            $encodingFormat = " -c \"{$this->encoding_format}\"";
        }

        $layoutAnalysis = "";
        if ($this->layout_analysis_include_figures) {
            $layoutAnalysis = ' -A';
        }

        $verticalWritingDetection = "";
        if ($this->vertical_writing_detection) {
            $verticalWritingDetection = ' -V';
        }

        return PythonTool::run("python " . self::LOCAL_SCRIPT_FULL_PATH . "{$passwordPDF}{$outputFilepath}{$encodingFormat}{$layoutAnalysis}{$verticalWritingDetection} \"{$fullPath}\\{$fileName}\"", $isThrowable);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setOutputFilename($filename)
    {
        // no final array output if got output filename
        // final array output has characters limit so it's not suitable for large PDF files
        if (in_array(pathinfo($filename, PATHINFO_EXTENSION), ["html", "txt", "xml"])) {
            $this->output_filename = $filename;
            return true;
        }

        return false;
    }

    // public function setLayoutAnalysisIncludeFiguresOn()
    // {
    //     $this->layout_analysis_include_figures = true;
    // }

    // public function setVerticalWritingDetectionOn()
    // {
    //     $this->vertical_writing_detection = true;
    // }
}
