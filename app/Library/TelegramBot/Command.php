<?php

namespace App\Library\TelegramBot;

use Exception;
use Log;
use App\Library\TelegramBot\Message;

class Command
{
    protected $commandsAvailable = [
        '/start',
        '/help',
        '/add',
        '/remove',
        '/list',
    ];

    protected $args;
    protected $from;
    protected $to;

    public function __construct()
    {

    }

    public function execCommand(string $cmd, string $args, string $from, string $to)
    {
        if(in_array($cmd, $this->commandsAvailable)) {
            $this->args = $args;
            $this->from = $from;
            $this->to = $to;
            $this->($cmd . 'Command')();
        }
        else {
            if(!$msg->sendMessage($to, 'unknow command')) {
                Log::error('error send unknow command message to : ' . $to);
            }
        }
    }

    public function startCommand()
    {

    }

    public function helpCommand()
    {
        
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
}