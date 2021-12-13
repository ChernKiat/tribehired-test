<?php

namespace App\Http\Controllers\HololiveFan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubtitleController extends Controller
{
    public function test()
    {
        // $path = public_path('myHololiveFan\mySubtitlesDownloader.js');
        exec("node public\myHololiveFan\mySubtitlesDownloader.js haha lol", $output, $variables);
        dd($output, $variables);
    }
}
