<?php

namespace App\Library\TelegramBot;
use GuzzleHttp\Client;
use Exception;
use Log;


class Request
{
    protected $client;
    protected $token;
    protected $tcpProxy;
    protected $botURL;
    protected $webhookURL;
    
    public function __construct()
    {
        $this->token = env('BOT_TOKEN');
        $this->tcpProxy = env('TCP_PROXY');
        $this->botURL = env('BOT_URL');
        $this->webhookURL = env('WEBHOOK_URL');
        if(!(strlen($this->token) + strlen($this->tcpProxy) + strlen($this->botURL) + strlen($this->webhookURL)> 0)) {
            throw new Exception(".env not set");
        }
        $this->client = new Client([
            'base_uri' => $this->botURL . $this->token . '/',
            'proxy' => $this->tcpProxy,
        ]);
    }

    public function deleteWebhook()
    {
        $response = $this->client->request('POST', 'deleteWebhook');
        $res = json_decode($response->getBody());
        return $res->ok;
    }

    public function setWebhook()
    {
        $response = $this->client->request('POST', 'setWebhook', [
            'form_params' => [
                    'url' => $this->webhookURL . $this->token,
                ],
            ]);
        $res = json_decode($response->getBody());
        return $res->ok;
    }

    /**
     * send message via telegram bot api
     * @param  int        $chat_id  chat_id message sent to
     * @param  string     $text     content
     * @param  array|null $optional sendmessage options
     * @return array                [
     *                                 'ok' => TRUE | FALSE,
     *                                 'message_id' => ,
     *                                 'date' => ,
     *                                 'text' => 
     *                                 ]
     */
    public function sendMessage(int $chat_id, string $text, array $optional = NULL)
    {
        $output = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];
        if(is_array($optional)) {
            $output += $optional;
        }
        $response = $this->client->request('POST', 'sendMessage', [
            'form_params' => $output,
        ]);
        $res = json_decode($response->getBody());
        if(!$res->ok) {
            Log::error('error sendmessage \'' . $text . '\' to : ' . $chat_id);
            return ['ok' => $res->ok];
        }
        return ['ok' => $res->ok,
                'message_id' => $res->result->message_id,
                'date' => $res->result->date,
                'text' => $res->result->text,
                ];
    }
    /*
    message object returned by tg:
    {
        "ok": true,
        "result": {
            "message_id": 1,
            "from": {
                "id": 222,
                "first_name": "1",
                "username": "1"
            },
            "chat": {
                "id": 1,
                "first_name": "1",
                "last_name": "1",
                "username": "1",
                "type": "private"
            },
            "date": 1494207661,
            "text": "1"
        }
    }
     */
}