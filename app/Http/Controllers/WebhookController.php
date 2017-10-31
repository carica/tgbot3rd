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
        //Log::info(json_encode($req->all()));
        //split commands and text messages into different services.
        $update = $req->all();
        $update_id = $update['update_id'];
        $message = $update['message'];
        if(isset($message['entities'])) {
            $entities = $message['entities'];
            foreach ($entities as $entity) {
                if($entity['type'] === 'bot_command') {
                    $input = $message['text'];
                    $first_space = strpos($input, ' ');
                    if($input[0] !== '/' || $first_space === FALSE) {
                    }
                    else {
                        $cmd->execCommand(substr($input, 1, $first_space - 1), substr($input, $first_space + 1), $message['from']['id'], $message['chat']['id']);
                    }
                }
            }
        }
    }
}
