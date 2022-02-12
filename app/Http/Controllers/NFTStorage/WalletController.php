<?php

namespace App\Http\Controllers\NFTStorage;

use App\Http\Controllers\Controller;
use App\Tools\FileTool;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function main()
    {
        return view('modules.nft_storage.wallet');
    }
}
