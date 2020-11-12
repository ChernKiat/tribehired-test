<?php

namespace Tests\Browser;

use App\Models\NetJunkies\Comment;
use App\Models\NetJunkies\Post;
use App\Tools\FileTool;
use App\Tools\FolderTool;
use App\Tools\StringTool;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NetJunkiesCrawlerTest extends DuskTestCase
{
    public function testSavePost()
    {
        $post = Post::whereNull('crawled_at')->first();
        if (!empty($post)) {
            $post->crawler_status = Post::CRAWLER_STATUS_RUN;
            $post->save();

            $this->browse(function(Browser $browser) use ($post) {
                $browser->visit($post->url);
                // $browser->visitRoute('welcome');

                if (!$this->facebookLogin($browser)) {
                    $post->crawler_status = Post::CRAWLER_STATUS_RUN;
                    $post->save();
                    dd('login failed...');
                }

                $postLocalPath = "\\storage\\posts\\{$post->id}";
                $postLocalFullPath = storage_path("app\\public{$postLocalPath}");
                if (true) {
                    $image = $this->facebookDownloadMainImage($browser, $postLocalFullPath);
                    if ($image) {
                        $post->image      = "{$postLocalPath}\\{$image}";
                        $post->main_type  = Post::MAIN_TYPE_IMAGE;
                        $post->save();
                    }
                } else {

                }

                $reaction = $this->facebookCrawlMainReaction($browser);
                $post->reaction_total  = $reaction;
                $post->save();

                $this->facebookClickViewMoreCommentsButton($browser);

                $comments = $this->facebookCrawlTopComments($browser, 50);
                foreach ($comments as $key => $comment) {
                    $comments[$key]['post_id'] = $post->id;
                }
                Comment::insert($comments);

                // $browser->downloadVideo($video->url, storage_path("videos\\{$video->id}"));
                // $post->video_name = $browser->page->videoFile;

                // $this->assertFileExists(storage_path("videos\\{$video->id}") . "\\{$video->video_name}");

                // $browser->pause(100000);
            });

            $post->crawled_at = Carbon::now();
            $post->crawler_status = Post::CRAWLER_STATUS_SUCCESS;
            $post->save();
        } else {

        }

        $this->assertTrue(true);
    }

    public function facebookLogin($browser)
    {
        try { $browser->waitFor('#email', 5); } catch (\Exception $e) {}

        $browser->type('email', 'yuna1450@live.com.my')
                // ->value('#email', 'yuna1450@live.com.my')
                ->type('pass', 'girl1450');
                // ->value('#pass', 'girl1450')

        try { $browser->waitFor('#loginbutton', 5); } catch (\Exception $e) {}

        if ($browser->element('#loginbutton')) {
            $browser->click('#loginbutton');
            return true;
        } elseif ($browser->element('#login_form > div:nth-child(2) > div:nth-child(3) > div[role="button"]:nth-child(1)')) {
            $browser->element('#login_form > div:nth-child(2) > div:nth-child(3) > div[role="button"]:nth-child(1)')
                    ->click('#loginbutton');
            return true;
        } else {
            return false;
        }
    }

    public function facebookDownloadMainImage($browser, $destination)
    {
        $url = $browser->waitFor('div[role="main"] div[data-pagelet="MediaViewerPhoto"] img', 5)
                            ->element('div[role="main"] div[data-pagelet="MediaViewerPhoto"] img')
                            ->getAttribute('src');
        // $image = $browser->script("return document.querySelector('div[role=\"main\"] div[data-pagelet=\"MediaViewerPhoto\"] img').getAttribute('src');");

        return FileTool::downloadAFile($url, $destination);
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
                'reaction_total' => StringTool::abbreviationsToIntegersConverter($comment->findElement(WebDriverBy::cssSelector('div:nth-child(2) > div:nth-child(1) > span:nth-child(1) > div:nth-child(1) > div:nth-child(1) > span:nth-child(2)'))->getText()),
            );
            // if (empty($temp['reaction'])) { $temp['reaction'] = 0; }
            $comments[] = $temp;
        }

        return $comments;
    }
}
