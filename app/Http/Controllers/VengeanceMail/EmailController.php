<?php

namespace App\Http\Controllers;

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
        Mail::to($user)->send(new SpamMail($user));
    }
}
