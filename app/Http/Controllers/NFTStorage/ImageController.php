<?php

namespace App\Http\Controllers\NFTStorage;

use App\Skins\NFTStorage\Gip;
use App\Skins\NFTStorage\GipImage;
use App\Skins\NFTStorage\GipMask;
use App\Skins\NFTStorage\GipSkin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function test()
    {
        // $gipSkin = GipSkin::test('a.jpg', public_path('myNFTStorage'));
        // dd('lol');

        // $gip = new Gip(public_path('myNFTStorage\a.jpg'));
        // dd($gip->createProtectImageResize(300, 500, new GipMask(new GipImage(public_path('myNFTStorage\a.jpg'))), new GipImage(public_path('myNFTStorage\a.jpg'))));

        return view('modules.nft_storage.base');
    }
}
