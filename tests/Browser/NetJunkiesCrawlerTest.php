<?php

namespace Tests\Browser;

use App\Models\NetJunkies\Post;
use App\Tools\FileTool;
use App\Tools\FolderTool;
use App\Tools\StringTool;
use Facebook\WebDriver\WebDriverBy;
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
                // $browser->visitRoute('welcome');

                $this->facebookLogin($browser);
                // $status = $this->facebookDownloadMainImage($browser, storage_path("app\\images\\{$post->id}"));
                // $total = $this->facebookCrawlMainReaction($browser);
                $this->facebookClickViewMoreCommentsButton($browser);
                $comments = $this->facebookCrawlTopComments($browser, 50);
                dd($comments, 'o0o');

                // $browser->downloadVideo($video->url, storage_path("videos\\{$video->id}"));
                // $post->video_name = $browser->page->videoFile;

                // $this->assertFileExists(storage_path("videos\\{$video->id}") . "\\{$video->video_name}");

                $browser->pause(100000);
            });

            $post->crawled_status = 3;
            $post->save();
        } else {

        }

        $this->assertTrue(true);
    }

    public function facebookLogin($browser)
    {
        try {
            $browser->waitFor('#email', 5);
        } catch (\Exception $e) {
        }

        if ($browser->element('#email')) {
            $browser->type('email', 'yuna1450@live.com.my')
                    // ->value('#email', 'yuna1450@live.com.my')
                    ->waitFor('#pass', 2)
                    ->type('pass', 'girl1450')
                    // ->value('#pass', 'girl1450')
                    ->waitFor('#loginbutton', 2)
                    ->click('#loginbutton');
        }
    }

    public function facebookDownloadMainImage($browser, $destination)
    {
        $url = $browser->waitFor('div[role="main"] div[data-pagelet="MediaViewerPhoto"] img', 5)
                            ->element('div[role="main"] div[data-pagelet="MediaViewerPhoto"] img')
                            ->getAttribute('src');
        // $image = $browser->script("return document.querySelector('div[role=\"main\"] div[data-pagelet=\"MediaViewerPhoto\"] img').getAttribute('src');");

        return file_exists(FileTool::downloadAFile($url, $destination));
    }

    public function facebookCrawlMainReaction($browser)
    {
        $total = $browser->waitFor('div[role="complementary"] div[role="button"] > span > span', 5)
                            ->element('div[role="complementary"] div[role="button"] > span > span')
                            ->getText();

        return StringTool::abbreviationsToIntegersConverter($total);
    }

    public function facebookClickViewMoreCommentsButton($browser)
    {
        $button = $browser->waitFor('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(4) > ul:nth-child(3)', 5)
                            ->scrollIntoView('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(4) > div:nth-child(4) > div:nth-child(1) > div[role="button"]:nth-child(2)')
                            // ->dragDown('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:last-child', 10)
                            ->pause(3000)
                            ->element('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(4) > div:nth-child(4) > div:nth-child(1) > div[role="button"]:nth-child(2)')
                            ->click();

        $browser->pause(5000);
        for ($i = 0; $i < 38; $i++) {
            $browser->dragUp('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:last-child', 5);
        }
    }

    public function facebookCrawlTopComments($browser, $total)
    {
        $commentsList = $browser->waitFor('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(4) > ul:nth-child(3)', 5)
                            ->elements('div[role="complementary"] > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(4) > ul:nth-child(3) > li div[role="article"] > div:nth-child(2) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1)');

        $comments = array();
        foreach ($commentsList as $comment) {
            if (count($comments) > $total) { break; }
            $temp = array(
                'comment' => $comment->findElement(WebDriverBy::cssSelector('div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:last-child > span:nth-child(1) > div:nth-child(1) > div:nth-child(1)'))->getText(),
                'reaction' => $comment->findElement(WebDriverBy::cssSelector('div:nth-child(2) > div:nth-child(1) > span:nth-child(1) > div:nth-child(1) > div:nth-child(1) > span:nth-child(2)'))->getText(),
            );
            // if (empty($temp['reaction'])) { $temp['reaction'] = 0; }
            $comments[] = $temp;
        }

        return $comments;
    }
}
