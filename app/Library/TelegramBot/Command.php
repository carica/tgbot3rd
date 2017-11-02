<?php

namespace App\Library\TelegramBot;

use Exception;
use Log;
use App\Library\TelegramBot\Request;
use App\User;
use App\Caller;

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
        }
        return $res;
    }

    public function startCommand()
    {
        return $this->req->sendMessage($this->to, 'Howdy ' . $this->user->firstname . $this->user->lastname . '! Welcome onboard!\nYou may /add to get service token.');
    }

    public function helpCommand()
    {
        return $this->req->sendMessage($this->to, 'remember to append name of your service as argument when using add and remove command.');
    }

    public function addCommand()
    {
        if(strlen($this->args) > 0) {
            $caller = new Caller();
            $caller->user_id = $this->user->id;
            $caller->status = Caller::available;
            $caller->name = substr($this->args, 0, 50); //max 50
            $caller->description = "not available yet";
            $caller->save();
            Log::info('successfully generated new caller: ' . $caller->id);
            $this->req->sendMessage($this->to, 'new sevice ' . $caller->name . ' generated!\n Its UUID for access is: ' . $caller->uuid);
            return TRUE;
        }
        else {
            $this->req->sendMessage($this->to, 'name of service missing. usage: /add {name of service}');
            Log::error('name of service missing, from ' . $this->from);
            return FALSE;
        }
    }

    public function removeCommand()
    {
        
    }

    public function listCommand()
    {
        
    }

    public function settingsCommand()
    {
        return TRUE;
    }
}