<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function postUpdate(Request $req)
    {
        Log::info(json_encode($req->all()));
    }
}
