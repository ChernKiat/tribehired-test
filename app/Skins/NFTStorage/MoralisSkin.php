<?php

namespace App\Skins\NFTStorage;

use App\Tools\FolderTool;
use ChernKiat\Moralis\Storage;

class MoralisSkin
{
    public static function uploadFolder($path)
    {
        $files = array();
        foreach (FolderTool::checkOrReturnAnyFileInFolder($path, false, false) as $file) {
            if (in_array($file, ['index.html', 'commands.txt'])) {
                continue;
            }

            switch (pathinfo($file, PATHINFO_EXTENSION)) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'ico':
                    $files[] = [
                        'path'     => public_path("{$path}\\{$file}"),
                        'content'  => base64_encode(file_get_contents("{$path}\\{$file}")),
                    ];
                    break;
                case 'json':
                default:
                    $files[] = [
                        'path'     => public_path("{$path}\\{$file}"),
                        'content'  => file_get_contents("{$path}\\{$file}"),
                    ];
                    break;
            }
        }
        dd((new Storage())->uploadFiles($files));
    }
}
