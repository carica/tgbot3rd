<?php

namespace App\Library\TelegramBot;

use Exception;
use Log;
use App\Library\TelegramBot\Request;
use App\User;
use App\Message;
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
            $this->{$cmd . 'Command'}();
        }
        else {
            $this->req->sendMessage($to, 'unknow command');
        }
    }

    public function startCommand()
    {
        $this->req->sendMessage($this->to, 'Howdy ' . $this->user->firstname . $this->user->lastname . '! Welcome onboard!\nYou may /add to get service token.');
    }

    public function helpCommand()
    {
        $this->req->sendMessage($this->to, 'remember to append name of your service as argument when using add and remove command,');
    }

    public function addCommand()
    {
        
    }

    public function removeCommand()
    {
        
    }

    public function listCommand()
    {
        
    }

    public function settingsCommand()
    {
        
    }
}