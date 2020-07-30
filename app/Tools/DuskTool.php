<?php

namespace App\Tools;

use Laravel\Dusk\Browser;

class DuskTool
{
    const LOCAL_DOWNLOAD_PATH = 'C:\Users\Kid14\Downloads';
    
    public static function waitUntilDownloadFinish(Browser $browser, $path)
    {
        foreach (FolderTool::checkOrReturnAnyFileInFolder(self::LOCAL_DOWNLOAD_PATH, false, false) as $localFile) {
            if (strpos($localFile, $videoTitle) !== false) {
                FolderTool::createFoldersRecursively($destination);
                FolderTool::renameOrMoveAFile(self::LOCAL_DOWNLOAD_PATH . "\\{$localFile}", $destination);
                $this->videoFile = $localFile;

                break;
            }
        }
    }
}
