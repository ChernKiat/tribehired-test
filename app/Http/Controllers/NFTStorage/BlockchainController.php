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
            'name'          => 'Test EVE',
            // 'image'         => route('nftstorage.image.response'),
            'image'         => 'https://sealkingdom.xyz/nft-storage/alpha-response-giga',
            // 'image_data'    => 'https://sealkingdom.xyz/nft-storage/alpha-response-giga',
            'description'   => 'Test',
            'external_url'  => 'https://sealkingdom.xyz/nft-storage/alpha-static', // the static image
        );

        dd(FileTool::createAFile(public_path("/myNFTStorage/Server/0.json"), json_encode($meta, JSON_UNESCAPED_SLASHES)));
    }
}
