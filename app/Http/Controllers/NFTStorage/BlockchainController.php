<?php

namespace App\Http\Controllers\NFTStorage;

use App\Http\Controllers\Controller;
use App\Tools\FileTool;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlockchainController extends Controller
{
    public function meta()
    {
        $meta = array(
            'image'         => 'https://sealkingdom.xyz/nft-storage/image/alpha-response',
            'description'   => 'Test',
            'name'          => 'Test EVE',
            'external_url'  => 'https://sealkingdom.xyz/nft-storage/image/alpha-static', // the static image
        );

        dd(FileTool::createAFile(public_path("/myNFTStorage/Server/0.json"), json_encode($meta)), json_encode($meta));
    }
}