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
        if (Carbon::now()->timestamp % 2) {
            return response()->file(public_path('/myNFTStorage/a.jpg'));
        } else {
            return response()->file(public_path('/myNFTStorage/c.jpg'));
        }
    }
}
