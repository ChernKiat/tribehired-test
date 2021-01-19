<?php
namespace App\Http\Controllers\ResearchDevelopment;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        return view('modules.research_development.test');
    }
}
