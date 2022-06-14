<?php

namespace App\Http\Controllers\SealChamber;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function __construct()
    {
    }

    public function roomCode() // landing
    {
        return view('modules.seal_chamber.room_code');
    }
}
