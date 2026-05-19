<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotRequestAndConfirmEmail
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();

        if ($bot_user->email) {
            $botSendMessage->handle($bot_user, 'SYS_CONFIRM_EMAIL');
        } else {
            return 'kkk www';
            $botSendMessage->handle($bot_user, 'SYS_REQUEST_EMAIL');
        }

        die();
    }
}
