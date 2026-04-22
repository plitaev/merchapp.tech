<?php

namespace App\Actions\Core\Max;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class MaxLinkFromTelegram
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();

        if (!$bot_user->max_user_id) {

            if ($bot_user->bot->max_alias) {
                $botSendMessage->handle($bot_user, 'SYS_LINK_MAX_FROM_TELEGRAM');
            } else {
                $botSendMessage->handle($bot_user, 'BOT_NOT_IN_MAX');
            }

        } else {
            $botSendMessage->handle($bot_user, 'SYS_USER_ALREADY_IN_MAX');
        }

    }
}
