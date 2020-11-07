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
        // $image = FileTool::downloadAFile("https://scontent.fkul13-1.fna.fbcdn.net/v/t1.0-9/109064205_3097115153670675_7409044947644634701_n.jpg?_nc_cat=105&ccb=2&_nc_sid=730e14&_nc_ohc=5_PsW2vh-i0AX8uYfVl&_nc_ht=scontent.fkul13-1.fna&oh=7349f86984c27461677d63bd04e5ef2c&oe=5FCD4CB2", storage_path("app\\images\\1"));
        //         dd($image ?? '');

        $post = Post::whereNull('crawled_at')->whereNull('crawled_status')->first();
        if (!empty($post)) {
            // $post->crawled_status = Post::STATUS_RUN;
            // $post->save();

            $this->browse(function(Browser $browser) use ($post) {
                $browser->visit($post->url);

                $this->facebookLogin($browser);
                // $status = $this->facebookDownloadImage($browser, storage_path("app\\images\\{$post->id}"));

                $image = $browser->waitFor('ul', 5)
                                    ->element('ul');
                dd($image, 'o0o');
                // mount_0_0 > div > div > div > div > div > div > div > div > div > div > div > div > div:nth-child(4) > ul > li:nth-child(1) > div:nth-child(1) > div > div > div > div > div > div._6cuy > div > div > div > span > div > div

                // $browser->downloadVideo($video->url, storage_path("videos\\{$video->id}"));
                // $post->video_name = $browser->page->videoFile;

                // $this->assertFileExists(storage_path("videos\\{$video->id}") . "\\{$video->video_name}");

                $browser->pause(1000000);
            });

            $post->crawled_status = 3;
            $post->save();
        } else {

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

        return file_exists(FileTool::downloadAFile($url, $destination));
    }
}
