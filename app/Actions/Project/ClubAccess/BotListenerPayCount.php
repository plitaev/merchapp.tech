<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;
use App\Models\Core\BotUser;

class BotListenerPayCount
{
    public function handle($webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

    }
}
