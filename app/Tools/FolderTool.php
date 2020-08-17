<?php

namespace App\Tools;

class FolderTool
{
    public static function checkOrReturnAnyFileInFolder($path, $isChecking = true, $includeFolders = true)
    {
        $files = array();
        if (is_dir($path)) {
            $files = array_diff(scandir($path), array('.', '..'));
            if (!$includeFolders) {
                foreach ($files as $key => $file) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    if(empty($extension)){
                        unset($files[$key]);
                    }
                }
            }

            if ($isChecking) {
                if(!empty($files)){
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            if ($isChecking) {
                return false;
            }
        }

        return $files;
    }

    public static function createFoldersRecursively($path)
    {
        if (!file_exists($path)) {
            $oldmask = umask(0);
            // \Illuminate\Support\Facades\File::makeDirectory($path, 0755, true);
            mkdir($path, 0755, true);
            umask($oldmask);
        }
    }
}
