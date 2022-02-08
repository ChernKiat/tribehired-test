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
            return response()->file(public_path('/myNFTStorage/0.png'));
            // return response()->file(public_path('/myNFTStorage/a.jpg'));
            // return response()->file(public_path('/myNFTStorage/a.jpg'), [
            //             'Pragma'         => 'no-cache',
            //             'Expires'        => 'Fri, 01 Jan 1990 00:00:00 GMT',
            //             'Cache-Control'  => 'private, no-store, no-cache, must-revalidate, max-age=0',
            //         ]);
        } else {
            return response()->file(public_path('/myNFTStorage/1.png'));
            // return response()->file(public_path('/myNFTStorage/c.jpg'));
            // return response()->file(public_path('/myNFTStorage/c.jpg'), [
            //             'Pragma'         => 'no-cache',
            //             'Expires'        => 'Fri, 01 Jan 1990 00:00:00 GMT',
            //             'Cache-Control'  => 'private, no-store, no-cache, must-revalidate, max-age=0',
            //         ]);
        }
    }
}
