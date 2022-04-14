<?php

namespace App\Http\Controllers\VanguardSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IPFSController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();

        return view('modules.vanguard_system.ipfs.show', compact('user'));
    }
}
