<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotEighteen
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();

        if ($bot_user->eighteen == 0) {
            $botSendMessage->handle($bot_user, 'SYS_REQUEST_EIGHTEEN');
            die();
        }

    }
}
