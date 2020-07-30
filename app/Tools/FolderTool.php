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
            mkdir($path, 0777, true);
            umask($oldmask);
        }
    }

    public static function emptyATextFile($path)
    {
        $realPath = realpath($path);
        if (is_writable($realPath)) {
            file_put_contents($realPath, "");

            return true;
        } else {
            return false;
        }
    }

    public static function renameOrMoveAFile($oldPath, $newPath)
    {
        if (!file_exists($newPath)) {
            rename($oldPath, $newPath);

            if (file_exists($newPath)) {
                return true;
            }
        }

        return false;
    }

    public static function deleteAFile($path)
    {
        $realPath = realpath($path);
        if (is_writable($realPath)) {
            unlink($realPath);

            return true;
        } else {
            return false;
        }
    }

    public static function convertBase64StringToImage($base64String, $path = "images/", $name = null)
    {
        $image = base64_decode($base64String);
        if (is_null($name)) {
            $name = uniqid();
        }
        self::createFoldersRecursively($path);
        $file = $path . $name . '.png';
        file_put_contents($file, $image);

        return $name . '.png';
    }
}
