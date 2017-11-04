<?php

namespace App\Library\TelegramBot;

use Exception;
use Log;
use App\Library\TelegramBot\Request;
use App\User;
use App\Caller;

/**
 * every command shall return true or false.
 */
class Command
{
    protected $commandsAvailable = [
        'start',
        'help',
        'add',
        'remove',
        'list',
        'settings',
    ];

    protected $args;
    protected $from;
    protected $to;
    //objects
    protected $req;
    protected $user;

    public function __construct()
    {
        $this->req = new Request();
    }

    public function execCommand(string $cmd, string $args, string $from, string $to)
    {
        if(in_array($cmd, $this->commandsAvailable)) {
            $this->args = $args;
            $this->from = $from;
            $this->to = $to;
            $this->user = User::where('tg_user_id', $from)->firstOrFail();
            $res = $this->{$cmd . 'Command'}();
        }
        else {
            $res = $this->req->sendMessage($to, 'unknow command');
            $res = $res['ok'];
        }
        return $res;
    }

    public function startCommand()
    {
        $res = $this->req->sendMessage($this->to, 'Howdy ' . $this->user->firstname . $this->user->lastname . '! Welcome onboard! You may /add {name of service} to get service token.');
        return $res['ok'];
    }

    public function helpCommand()
    {
        $res = $this->req->sendMessage($this->to, 'remember to append name of your service as argument when using add and remove command. your service url is: POST '. env('WEBHOOK_URL') . 'v1/send PARAMS: uuid = uuid_of_your_sercie, message = your_message');
        return $res['ok'];
    }

    public function addCommand()
    {
        if(strlen($this->args) > 0) {
            $caller = new Caller();
            $caller->user_id = $this->user->id;
            $caller->status = Caller::available;
            $caller->name = substr($this->args, 0, 50); //max 50
            $caller->description = "not available yet";
            $caller->chat_id = $this->to;
            $caller->save();
            Log::info('successfully generated new caller: ' . $caller->id);
            $this->req->sendMessage($this->to, 'new sevice ' . $caller->name . ' generated! Its UUID for access is: ' . $caller->uuid);
            return TRUE;
        }
        else {
            Log::error('name of service missing, from ' . $this->from);
            $this->req->sendMessage($this->to, 'name of service missing. usage: /add {name of service}');
            return FALSE;
        }
    }

    public function removeCommand()
    {
        if(strlen($this->args) > 0) {
            $caller = Caller::where('uuid', $this->args)->firstOrFail();
            Log::info($this->args);
            if(isset($caller) && $caller->user_id === $this->user->id) {
                $name = $caller->name;
                $caller_id = $caller->id;
                $caller->delete();
                Message::where('caller_id', $caller_id)->delete();
                Log::info('successfully deleted caller: ' . $name);
                $this->req->sendMessage($this->to, 'successfully deleted caller: ' . $name);
            return TRUE;
            }
            else {
                Log::info('wrong uuid or no permission: ' . $this->args);
                $this->req->sendMessage($this->to, 'wrong uuid or no permission: ' . $this->args);
                return FALSE;
            }
        }
        else {
            Log::error('uuid missing, from ' . $this->from);
            $this->req->sendMessage($this->to, 'uuid missing. usage: /remove {uuid of service}');
            return FALSE;
        }
    }

    public function listCommand()
    {
        $callers = Caller::where('user_id', $this->user->id)->get();
        foreach ($callers as $caller) {
            $this->req->sendMessage($this->to, 'name: ' . $caller->name . ' uuid: ' . $caller->uuid);
        }
        return TRUE;
    }

    public function settingsCommand()
    {
        return TRUE;
    }
}