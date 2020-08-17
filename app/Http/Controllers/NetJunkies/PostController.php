<?php
namespace App\Http\Controllers\NetJunkies;

use App\Http\Controllers\Controller;
use App\Models\NetJunkies\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('net_junkies.posts.crawler');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'url'  => 'required',
        ]);

        $sql = new Post();
        $sql->url     = $request->input('url');

        $sql->setPostSource();

        $sql->save();

        return redirect()->route('netjunkies.post.create')->with('success', 'Successfully added New URL');
    }
}
