<?php
namespace App\Http\Controllers\Project;

use App\Actions\Core\BotUser\BotUserCreateFromTelegram;
use App\Actions\Core\Telegram\TelegramGetChatIDFromWebhook;
use App\Actions\Core\Telegram\TelegramWebhookWrite;
use App\Http\Controllers\Controller;

class App2BotController extends Controller
{
    public function app2bot() {
        $bot_id = 2;

        $telegramChatCreate = new BotUserCreateFromTelegram();
        $telegramGetChatIDFromWebhook = new TelegramGetChatIDFromWebhook();
        $telegramWebhookWrite = new TelegramWebhookWrite();

        $webhook = $telegramWebhookWrite->handle(file_get_contents('php://input'), $bot_id);
        $chat_id = $telegramGetChatIDFromWebhook->handle($webhook);

        $telegramChatCreate->handle($chat_id, $bot_id, $webhook);
    }
}
