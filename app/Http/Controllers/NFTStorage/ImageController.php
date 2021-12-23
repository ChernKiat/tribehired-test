<?php

namespace App\Http\Controllers\NFTStorage;

use App\Skins\NFTStorage\Gip;
use App\Skins\NFTStorage\GipSkin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function test()
    {
        $gipSkin = GipSkin::test('a.jpg', public_path('myNFTStorage'));
        dd('lol');

        $gip = new Gip('srcImg/im1.jpg');
        imagecolortransparent($im, $black);
        dd($gip->createProtectImageResize(300, 500));

        return view('modules.nft_storage.test');
    }
}
