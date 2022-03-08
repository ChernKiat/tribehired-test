<?php

namespace App\Http\Controllers\NFTStorage;

use App\Http\Controllers\Controller;
use App\Models\NFTStorage\Maneki;
use Illuminate\Http\Request;

class ManekiController extends Controller
{
    public function main()
    {
        return view('modules.nft_storage.maneki');
    }

    public function image(Request $request, $sha256, $index)
    {
        $maneki = Maneki::where('index', $index)->first();
        if ($maneki->sha256 == $sha256) {
            return response()->file($maneki->image);
        } else {
            abort(404);
        }
    }

    public function test()
    {
        return view('modules.nft_storage.test');
    }
}
