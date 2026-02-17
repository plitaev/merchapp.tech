<?php
namespace App\Http\Controllers\Project;

use App\Actions\Core\BotUser\BotUserCreateFromMax;
use App\Actions\Core\Max\MaxGetChatIDFromWebhook;
use App\Actions\Core\Max\MaxWebhookWrite;
use App\Http\Controllers\Controller;

class App2MaxBotController extends Controller
{
    public function app2bot() {
        $bot_id = 2;

        $maxChatCreate = new BotUserCreateFromMax();
        $maxGetChatIDFromWebhook = new MaxGetChatIDFromWebhook();
        $maxWebhookWrite = new MaxWebhookWrite();

        $webhook = $maxWebhookWrite->handle(file_get_contents('php://input'), $bot_id);
        $chat_id = $maxGetChatIDFromWebhook->handle($webhook);

        $maxChatCreate->handle($chat_id, $bot_id, $webhook);
    }
}
