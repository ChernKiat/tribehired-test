<?php

namespace App\Tools;

use Excel;

class ExcelTool
{
    public function renderExcelContent($request, $field, $limit = 0)
    {
        $data = [];
        $path = $request->file($field)->getRealPath();
        $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) use ($limit) {
            // $reader->dd();
            $reader->ignoreEmpty(); // ignore empty headings (headings still remain, only rows are removed)
            if (!empty($limit)) {
                $reader->limitRows($limit);
            }
        })->get();

        return $data;
    }

    public static function exportExcel($template = [], $name = 'Template', $doc = 'xls', $titles = [], $isDownload = true, $savePath = null)
    {
        $myExcel = Excel::create($name, function($excel) use ($template, $titles) {
            $excel->sheet('Data', function($sheet) use ($template, $titles) {
                if (!empty($titles)) {
                    $sheet->appendRow($titles);
                    foreach ($template as $value) {
                        $sheet->appendRow($value);
                    }
                } else {
                    $sheet->fromArray($template, null, 'A1', true); // overwrite the No Display 0 default setting
                }
            });
        });

        if ($isDownload) {
            $myExcel->download($doc);
        } else {
            if (!empty($savePath)) {
                $myExcel->store($doc, $savePath);
            } else {
                $myExcel->store($doc);
            }
        }
    }
}
