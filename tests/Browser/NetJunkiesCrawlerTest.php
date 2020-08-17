<?php

namespace Tests\Browser;

use App\Models\NetJunkies\Post;
use App\Tools\FileTool;
use App\Tools\FolderTool;
use App\Tools\CodeTool;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NetJunkiesCrawlerTest extends DuskTestCase
{
    public function testSavePost()
    {
        $post = Post::whereNull('crawled_at')->whereNull('crawled_status')->first();
        if (!empty($post)) {
            // $post->crawled_status = 3;
            // $post->save();

            $this->browse(function(Browser $browser) use ($post) {
                $browser->visit($post->url);

                $this->facebookLogin($browser);
                $this->facebookDownloadImage($browser, storage_path("app\\images\\{$post->id}"));


                $image = $browser->waitFor('ul', 5)
                                    ->element('ul');
                dd($image ?? '');
                // mount_0_0 > div > div > div > div > div > div > div > div > div > div > div > div > div:nth-child(4) > ul > li:nth-child(1) > div:nth-child(1) > div > div > div > div > div > div._6cuy > div > div > div > span > div > div

                // $browser->downloadVideo($video->url, storage_path("videos\\{$video->id}"));
                // $post->video_name = $browser->page->videoFile;

                // $this->assertFileExists(storage_path("videos\\{$video->id}") . "\\{$video->video_name}");

                $browser->pause(1000000);
            });

            $post->crawled_status = 3;
            $post->save();
        }

        $this->assertTrue(true);
    }

    public function facebookLogin($browser)
    {
        $browser->waitFor('#email', 2)
                ->type('email', 'yuna1450@live.com.my')
                // ->value('#email', 'yuna1450@live.com.my')
                ->waitFor('#pass', 2)
                ->type('pass', 'girl1450')
                // ->value('#pass', 'girl1450')
                ->waitFor('#loginbutton', 2)
                ->click('#loginbutton');
    }

    public function facebookDownloadImage($browser, $destination)
    {
        $url = $browser->waitFor('div[data-pagelet="page"] div[data-pagelet="MediaViewerPhoto"] img', 5)
                            ->element('div[data-pagelet="page"] div[data-pagelet="MediaViewerPhoto"] img')
                            ->getAttribute('src');
        // $image = $browser->script("return document.querySelector('div[data-pagelet=\"page\"] div[data-pagelet=\"MediaViewerPhoto\"] img').getAttribute('src');");

        $image = FileTool::downloadAFile($url, storage_path("app\\images\\1\\a.jpg"));
        file_put_contents(storage_path("app\\images\\1\\a.jpg"), file_get_contents($url));
        copy($image, storage_path("app\\images\\a.jpg"));
    }
}
