<?php
/**
 * File defines class for a console command to send
 * email notifications to users
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

/**
 * Class WebhookCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class removeWebhookCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "webhook:remove";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "remove webhook url";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $token = env('BOT_TOKEN');
        $tcpProxy = env('TCP_PROXY');
        if(strlen($token) > 0) {
            $client = new Client(['base_uri' => env('BOT_URL') . $token . '/']);
            $response = $client->request('POST', 'deleteWebhook', [
                'proxy' => [
                    'http'  => $tcpProxy, // Use this proxy with "http"
                    'https' => $tcpProxy, // Use this proxy with "https",
                ],
            ]);
            $res = json_decode($response->getBody());
            $this->info($res->description);
        }
        else {
            $this->info('token not set');
        }
    }
}