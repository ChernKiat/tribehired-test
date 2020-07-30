<?php

namespace App\Http\Controllers;

use App\Models\VideoChannel\Playlist;
use App\Models\VideoChannel\Video;
use App\Http\Components\VideoChannelComponent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['test']]);
    }

    public function index()
    {
        return view('home');
    }

    public function ws()
    {
        return view('ws.index');
    }

    public function scrabble()
    {
        return view('math_genius.scrabble');
    }

    public function mathGenius()
    {
        return view('math_genius.index');
    }

    public function netJunkies()
    {
        return view('net_junkies.index');
    }

    public function gmtk()
    {
        return view('game_jam.gmtk.index');
    }

    public function mashUp()
    {
        return view('game_jam.mash_up.index');
    }

    public function test()
    {
        return view('jackbox.form');
    }

    public function video()
    {
        $playlist_meta = [
            'name'      => 'Test',
            'platform'  => VideoChannelComponent::PLATFORM_YOUTUBE,
        ];
        $playlist = Playlist::updateOrCreate($playlist_meta, $playlist_meta);

        $video = new Video();
        $video->playlist_id  = $playlist->id;
        $video->url          = 'https://www.youtube.com/watch?v=YjvSyWInHeE';
        $video->save();
    }
}
