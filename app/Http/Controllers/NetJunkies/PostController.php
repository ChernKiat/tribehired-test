<?php
namespace App\Http\Controllers\NetJunkies;

use App\Http\Controllers\Controller;
use App\Http\Models\NetJunkies\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('net_junkies.posts.crawler');
    }

    public function store(Request $request)
    {
        $sql = new Post();
        $sql->source  = $source;
        $sql->url     = $request->input('url');
        $sql->save();

        $posts = TribeHiredAPITool::getPostsIndexWithComments($request->input('search'));

        $output = array();

        return redirect()->route('netjunkies.post.create')->with('success', 'Successfully added New URL');
    }
}
