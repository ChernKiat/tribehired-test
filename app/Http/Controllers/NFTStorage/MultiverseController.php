<?php

namespace App\Http\Controllers\NFTStorage;

use App\Http\Controllers\Controller;
use App\Models\NFTStorage\Multiverse;
use Illuminate\Http\Request;

class MultiverseController extends Controller
{
    public function main()
    {
        return view('modules.nft_storage.c');
    }

    public function image(Request $request, $sha256, $multiverse)
    {
        $multiverse = Multiverse::with(['asset' => function ($query) use ($sha256) {
                            $query->where('sha256', $sha256);
                        }])->where('keyword', $multiverse)->first();
        if (!empty($multiverse->asset)) {
            return response()->file($multiverse->asset->image);
        } else {
            abort(404);
        }
    }

    public function external(Request $request, $sha256, $multiverse)
    {
        $multiverse = Multiverse::with(['asset' => function ($query) use ($sha256) {
                            $query->where('sha256', $sha256);
                        }])->where('keyword', $multiverse)->first();
        if (!empty($multiverse->asset)) {
            return response()->file($multiverse->asset->original_image);
        } else {
            abort(404);
        }
    }

    public function test()
    {
        // Multiverse::seed();

        $multiverse = Multiverse::with(['assets'])->where('name', 'Maneki Zodiac')->first();
        // $multiverse->generateContractMeta();
        // $multiverse->generateMetas();
        // $multiverse->generateBlackImages();
        // $multiverse->generateBaseCustomImages();
        $multiverse->generateCustomImages();
        // $multiverse->getMetaAttribute();
        // $multiverse->refreshMetas();
        dd('yay');

        // https://sealkingdom.xyz/8d94179d0989f6f99ec7b55c5f2d590ccdeca0f7a7a4c25a671259cc93e47f8b/multiverse/maneki_zodiac
        // https://sealkingdom.xyz/244bb24e51b88aecc08dee721d486d0f418b2de2ca219be707ac00247c8bd47f/multiverse/maneki_zodiac
        // https://sealkingdom.xyz/71e95720ed18e0b119a27c91b7312d758ba30823bf43350dfd566988e75fc1f6/multiverse/maneki_zodiac
    }
}
