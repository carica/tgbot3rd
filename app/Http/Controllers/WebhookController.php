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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * split commands and text messages to activate command instance.
     * @param  Request $req [description]
     * @param  Command $cmd TelegramBot\Command class injection
     * @return nothing. 
     */
    public function postUpdate(Request $req, Command $cmd)
    {
        //Log::info(json_encode($req->all()));
        $update = $req->all();
        $update_id = $update['update_id'];
        $message = $update['message'];
        /*
        update object with command recieved from private chat:
        {
            "update_id": 111,
            "message": {
                "message_id": 111,
                "from": {
                    "id": 111,
                    "is_bot": false,
                    "first_name": "1",
                    "last_name": "1",
                    "username": "1",
                    "language_code": "en-CN"
                },
                "chat": {
                    "id": 1,
                    "first_name": "1",
                    "last_name": "1",
                    "username": "1",
                    "type": "private"
                },
                "date": 1509292064,
                "text": "/start",
                "entities": [
                    {
                        "offset": 0,
                        "length": 6,
                        "type": "bot_command"
                    }
                ]
            }
        }
        */

        //save to user table in the case of 1st time access.
        //set status to 0
        //status is set to 1 if 1st caller uuid is assigned.
        $from = $message['from'];
        $from_id = $from['id'];
        $user = User::firstOrNew(['tg_user_id' => $from_id]);
        if(!isset($user->id)) {
            //Log::info($from_id . ' not exist.');
            $user->tg_user_id = $from_id;
            $user->username = $from['username'];
            $user->firstname = $from['first_name'];
            $user->lastname = $from['last_name'];
            $user->status = User::started;
            $user->save();
        }
        $input = $message['text'];
        $chat_id = $message['chat']['id'];

        //if command
        $is_command = FALSE;
        if(isset($message['entities'])) {
            $entities = $message['entities'];
            foreach ($entities as $entity) {
                if($entity['type'] === 'bot_command') {
                    $is_command = TRUE;
                    break;
                }
            }
        }
        //save update object to db
        $update = Update::firstOrNew(['update_id' => $update_id]);
        if(isset($update->id) && $update->status === Update::requestDone) {
            $update->user_id = $user->id;
            $update->text = $input;
            $update->update_id = $update_id;
            $update->chat_id = $chat_id;
            $update->update_time = Carbon::createFromTimestamp($message['date']);
            $update->type = $is_command ? Update::typeCommand : Update::typeOther; //update type: command = 1
            $update->save();
        }
        //split command and argument
        if($is_command){
            $first_space = strpos($input, ' ');
            if($first_space === FALSE) {
                $cmd->execCommand(substr($input, 1), '', $from_id, $chat_id);
            }
            else {
                $cmd->execCommand(substr($input, 1, $first_space - 1), substr($input, $first_space + 1), $from_id, $chat_id);
            }
        }
        else {
            Log::warning('unknow message: ' . $message['text']);
        }
    }
}
