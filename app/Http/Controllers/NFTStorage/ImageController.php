<?php

namespace App\Http\Controllers\NFTStorage;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function alpha()
    {
        return view('modules.nft_storage.test');
    }

    public function response()
    {
        if (Carbon::now()->timestamp % 2 == 1) {
            // return response()->file(public_path('/myNFTStorage/a.jpg', ['Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0']));
            // return response()->file(public_path('/myNFTStorage/a.jpg', ['Cache-Control' => 'no-store;no-cache;must-revalidate;post-check=0;pre-check=0;max-age=0']));
            return response()->file(public_path('/myNFTStorage/a.jpg'));
        } else {
            // return response()->file(public_path('/myNFTStorage/c.jpg', ['Cache-Control' => 'no-store;no-cache;must-revalidate;post-check=0;pre-check=0;max-age=0']));
            return response()->file(public_path('/myNFTStorage/c.jpg'));
        }
    }
}
