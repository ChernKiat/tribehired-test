<?php

namespace App\Tools;

class FileTool
{
    public static function extractFileDetails($path, $part = null)
    {
        $pathParts = pathinfo($path);
        if (!is_null($part)) {
            switch ($part) {
                case 'extension':
                    return $pathParts['extension'];
                    break;
                case 'filename':
                    return $pathParts['filename'];
                    break;
                default:
                    return false;
                    break;
            }
        } else {
            return $pathParts;
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

    public static function downloadAFile($url, $path)
    {
        FolderTool::createFoldersRecursively($path);

        $image = CodeTool::randomGenerator('filename') . '.' . FileTool::extractFileDetails($url, 'extension');
        return file_put_contents("{$path}\\{$image}", file_get_contents($url));
        // return copy($url, "{$path}\\{$image}");
    }

    public static function convertBase64StringToImage($base64String, $path = "images/", $name = null)
    {
        $image = base64_decode($base64String);
        if (is_null($name)) {
            $name = uniqid();
        }
        FolderTool::createFoldersRecursively($path);
        $file = $path . $name . '.png';
        file_put_contents($file, $image);

        return $name . '.png';
    }
}
