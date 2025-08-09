<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotCabinetRecurrentCheck
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();

        if ($bot_user->recurrent == 1) {
            $botSendMessage->handle($bot_user, 'SYS_CABINET_RECURRENT_IS_ENABLED');
        } else {
            $botSendMessage->handle($bot_user, 'SYS_CABINET_RECURRENT_IS_DISABLED');
        }
    }
}
