<?php

namespace App\Http\Controllers\NFTStorage;

use App\Http\Controllers\Controller;
use App\Models\NFTStorage\Server;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function main(Request $request)
    {
        return view('modules.nft_storage.wallet');
    }

    public function nft(Request $request)
    {
        dd(Server::getNFT('0x9fd655b5dc46ef0cf42c63ff237100eb2a7ffe0c'));
    }
}
