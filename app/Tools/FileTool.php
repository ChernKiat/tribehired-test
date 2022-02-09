<?php

namespace App\Tools;

use File;

class FileTool
{
    public static function extractURLDetails($url, $part = null)
    {
        $urlParts = parse_url($url);

        if (!is_null($part)) {
            switch ($part) {
                case 'path':
                case 'query':
                case 'scheme':
                    return $urlParts[$part];
                    break;
                default:
                    return false;
                    break;
            }
        } else {
            return $urlParts;
        }
    }
    public static function extractFileDetails($path, $part = null)
    {
        $pathParts = pathinfo($path);

        if (!is_null($part)) {
            switch ($part) {
                case 'extension':
                case 'filename':
                    return $pathParts[$part];
                    return $pathParts[$part];
                    break;
                default:
                    return false;
                    break;
            }
        } else {
            return $pathParts;
        }
    }

    public static function createAFile($path, $content)
    {
        FolderTool::createFoldersRecursively($path);

        // if (is_writable($path)) {
            file_put_contents($path, $content);
            // File::chmod($path, 0664);

            return true;
        // } else {
        //     return false;
        // }
    }

    public static function emptyATextFile($path)
    {
        return self::createAFile($path, "");
    }

    public static function deleteAFile($path)
    {
        if (is_writable($path)) {
            unlink($path);

            return true;
        } else {
            return false;
        }
    }

    public static function downloadAFile($url, $path)
    {
        FolderTool::createFoldersRecursively($path);

        $file = StringTool::randomGenerator('filename') . '.' . FileTool::extractFileDetails(FileTool::extractURLDetails($url, 'path'), 'extension');
        $localfile = "{$path}\\{$file}";
        file_put_contents($localfile, file_get_contents($url));
        // copy($url, $localfile);

        if (file_exists($localfile)) {
            return $file;
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
        FolderTool::createFoldersRecursively($path);
        $file = $path . $name . '.png';
        file_put_contents($file, $image);

        return $name . '.png';
    }
}
