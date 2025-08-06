<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotMainMenuMessage
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();
        $botSendMessage->handle($bot_user, 'SYS_MAIN_MENU_MESSAGE');
    }
}
