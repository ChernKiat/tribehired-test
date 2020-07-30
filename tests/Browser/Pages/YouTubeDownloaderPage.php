<?php

namespace Tests\Browser\Pages;

use App\Tools\FolderTool;
use Laravel\Dusk\Browser;

class YouTubeDownloaderPage extends Page
{
    public $videoFile = false;

    public function url()
    {
        return 'https://www.y2mate.com/en19';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        //
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@url_input' => '#txt-url',
            '@url_button' => '#btn-submit',
            '@download_button' => '#dl-btns a',
        ];
    }

    public function downloadVideo(Browser $browser, $url, $destination)
    {
        $browser->waitFor('@url_input', 2)
                ->value('@url_input', $url)
                // ->type('query', $url_input)
                ->click('@url_button')
                ->waitFor('#result .tab-content');

        $downloadButtonClicked = false;
        $videoTitle = $browser->text('.thumbnail .caption b');
        $videoTitle = str_replace("|", "_", $videoTitle);

        $myStandardFormats = array('mp4', 'mp3');
        $myStandardResolutions = array('720', '360');
        foreach ($myStandardFormats as $myStandardFormat) {
            $format = $browser->elements("#{$myStandardFormat}");
            if (!empty($format)) {
                $possibleResolutions = $browser->elements("#{$myStandardFormat} td:nth-child(1)");
                foreach ($myStandardResolutions as $myStandardResolution) {
                    foreach ($possibleResolutions as $key => $possibleResolution) {
                        if (strpos($possibleResolution->getText(), $myStandardResolution) !== false) {
                            $browser->click("#{$myStandardFormat} tr:nth-child(" . ($key + 1) . ") td:nth-child(3) a")
                                    ->waitFor('#progress-bar', 10)
                                    ->waitFor('@download_button', 10)
                                    ->click('@download_button');

                            $downloadButtonClicked = true;
                            break;
                        }
                    }

                    foreach (FolderTool::checkOrReturnAnyFileInFolder(self::LOCAL_DOWNLOAD_PATH, false, false) as $localFile) {
                        if (strpos($localFile, $videoTitle) !== false) {
                            FolderTool::createFoldersRecursively($destination);
                            FolderTool::renameOrMoveAFile(self::LOCAL_DOWNLOAD_PATH . "\\{$localFile}", $destination);
                            $this->videoFile = $localFile;

                            break;
                        }
                    }

                    if ($this->videoFile) {
                        break;
                    }
                }
                break;
            }
        }
    }
}
