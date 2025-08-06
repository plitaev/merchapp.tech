<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\BotUser\BotUserGetByEmail;
use App\Actions\Core\Telegram\TelegramSendMessage;
use App\Http\Controllers\Controller;

class BotMessageController extends Controller
{
    public function send_to_admin(int $bot_message_id) {
        $botUserGetByEmail = new BotUserGetByEmail();
        $telegramSendMessage = new TelegramSendMessage();

        $bot_user = $botUserGetByEmail->handle(auth()->user()->email);
        if ($bot_user) {
            return $telegramSendMessage->handle($bot_user, $bot_message_id);
        }

    }
}
