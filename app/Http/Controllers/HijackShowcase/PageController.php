<?php

namespace App\Http\Controllers\HijackShowcase;

use App\Http\Controllers\Controller;
use App\Models\HijackShowcase\Envato;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, $team, $link)
    {
        $project = Envato::link($team, $link);

        return view('modules.hijack_showcase.new', compact('project'));
    }
}
