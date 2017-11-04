<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;
use App\Library\TelegramBot\Request as telegramRequest;
use Log;
use App\Caller;
use App\Message;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function postSend(httpRequest $req, telegramRequest $tgReq)
    {
        $res = [];
        if($req->has(['uuid', 'message'])) {
            $caller = Caller::where('uuid', $req->input('uuid'))->first();
            if(isset($caller)) {
                $result = $tgReq->sendMessage($caller->chat_id, $req->input('message'));
                if($result['ok']) {
                    $message = new Message();
                    $message->tg_message_id = $result['message_id'];
                    $message->tg_date = Carbon::createFromTimestamp($result['date']);
                    $message->chat_id = $caller->chat_id;
                    $message->caller_id = $caller->id;
                    $message->user_id = $caller->user_id;
                    $message->content = substr($req->input('message'), 0, 199);
                    $message->text = substr($result['text'], 0, 199);
                    $message->save();
                    $res = [
                        'ok' => TRUE,
                        'result' => 'message sent.',
                    ];
                }
                else {
                    $res = [
                        'ok' => FALSE,
                        'result' => 'error send',
                    ];
                }
            }
            else {
                $res = [
                    'ok' => FALSE,
                    'result' => 'uuid not found',
                ];
            }
        }
        else {
            $res = [
                'ok' => FALSE,
                'result' => 'parameters missing',
            ];
        }
        return response()->json($res);
    }
}