<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\TelegramBot\Command;
use Log;
use App\User;
use App\Update;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public function postSend(Request $req, Command $cmd)
    {
        if($req->input->has(['uuid', 'message'])) {

        }
        else {
            return reponse()->json([
                'ok' => FALSE,
                'result' => 'parameters missing',
            ]);
        }
    }
}