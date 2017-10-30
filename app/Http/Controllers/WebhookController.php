<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\TelegramBot\Command;
use Log;

class WebhookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function postUpdate(Request $req, Command $cmd)
    {
        Log::info(json_encode($req->all()));
        //split commands and text messages into different services.
    }
}
