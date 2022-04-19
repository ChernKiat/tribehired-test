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
            return response()->file($maneki->image_demo);
        } else {
            abort(404);
        }
    }

    public function static(Request $request, $maneki)
    {
        'https://api.opensea.io/api/v1/asset/<your_contract_address>/<token_id>/?force_update=true';
    }

    public function seed()
    {
        Maneki::seed();
        dd('lol');
    }

    public function test()
    {
        Maneki::seed();
        // Maneki::generateMetas();
        // Maneki::generateContractMeta();
        dd('yay');
        return view('modules.nft_storage.test');
    }
}
