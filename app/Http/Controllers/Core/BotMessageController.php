<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Actions\Core\BotMessage\BotMessageGetByID;
use App\Actions\Core\BotUser\BotUserGetByEmail;
use App\Actions\Core\Telegram\TelegramSendMessage;

class BotMessageController extends Controller
{
    public function send_to_admin(int $bot_message_id) {
        $botMessageGetByID = new BotMessageGetByID();
        $botUserGetByEmail = new BotUserGetByEmail();
        $telegramSendMessage = new TelegramSendMessage();

        $bot_message = $botMessageGetByID->handle($bot_message_id);

        $bot_user = $botUserGetByEmail->handle($bot_message->bot_id, auth()->user()->email);
        if ($bot_user) {
            return $telegramSendMessage->handle($bot_user, $bot_message_id);
        }

    }
}
