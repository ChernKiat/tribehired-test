<?php

namespace Tests\Browser\keep;

use App\Models\VideoChannel\Video;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\YouTubeDownloaderPage;
use Tests\DuskTestCase;

class VideoDownloaderTest extends DuskTestCase
{
    public function testDownload()
    {
        $video = Video::whereNull('video_name')->first();
        if (!empty($video)) {
            $video->video_name = "";

            $this->browse(function (Browser $browser) use ($video) {
                $browser->visit(new YouTubeDownloaderPage())
                        ->downloadVideo($video->url, storage_path("videos\\{$video->id}"));
                $video->video_name = $browser->page->videoFile;

                $this->assertFileExists(storage_path("videos\\{$video->id}") . "\\{$video->video_name}");

                $browser->pause(1000000);
            });

            $video->save();
        }

        $this->assertTrue(true);
    }
}
