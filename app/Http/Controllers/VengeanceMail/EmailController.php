<?php

namespace App\Http\Controllers\VengeanceMail;

use App\Http\Controllers\Controller;
use App\Mail\SpamMail;
use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['test']]);
    }

    public function sendSpamMail()
    {
        Mail::to("Kid1450@live.com")->send(new SpamMail("Kid1450@live.com"));
    }
}
