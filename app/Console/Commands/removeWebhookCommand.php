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
use App\Library\TelegramBot\Request;

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
    public function handle(Request $req)
    {
        if($req->deleteWebhook()) {
            $this->info('webhook deleted');
        }
        else {
            $this->info('fail to delete webhook');
        }
    }
}