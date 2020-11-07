<?php
namespace App\Http\Controllers\ResearchDevelopment;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function barcode()
    {
        return view('research_development.barcode');
    }
}
