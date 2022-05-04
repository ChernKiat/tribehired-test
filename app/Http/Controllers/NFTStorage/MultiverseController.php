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
                        }])->where('name', $multiverse)->first();
        if (!empty($multiverse->asset)) {
            return response()->file($multiverse->asset->image);
        } else {
            abort(404);
        }
    }

    public function test()
    {
        $multiverse = Multiverse::with(['asset'])->where('name', 'Maneki Zodiac')->first();
        $multiverse->generateMetas();
        // $multiverse->getMetaAttribute();
        // $multiverse->refreshMetas();
        dd('yay');
    }
}
