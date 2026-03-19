<?php
namespace App\Actions\Project\ClubAccess;
use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotWelcomeMessage
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();
        if ($bot_user->sys_welcome_message_status == 0) {
            return $botSendMessage->handle($bot_user, 'SYS_WELCOME_MESSAGE');
        }
    }
}
