<?php

namespace App\Http\Controllers\StartupDemo;

use App\Tools\ImageTool;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function main()
    {
        return view('modules.startup_demo.main');
    }

    public function pageCalcudoku()
    {
        return view('modules.startup_demo.calcudoku');
    }

    public function pageEasterCard()
    {
        return view('modules.startup_demo.easter_card');
    }

    public function pageSlotMachine()
    {
        return view('modules.startup_demo.slot_machine');
    }
}
