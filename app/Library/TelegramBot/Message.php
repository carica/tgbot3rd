<?php

namespace App\Library\TelegramBot;
use GuzzleHttp\Client;
use Exception;
use Log;

class Message
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
}