<?php

namespace App\Http\Controllers\ProfileLanding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request)
    {
        $project = Envato::link($team, $link);

        return view('modules.profile_landing.new', compact('project'));
    }
}
