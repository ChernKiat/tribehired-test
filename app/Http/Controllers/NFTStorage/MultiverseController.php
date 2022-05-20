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
    }
}
