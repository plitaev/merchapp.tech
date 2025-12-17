<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;

class BotListenerPayCount
{
    public function handle($webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();

        if (isset($webhook['message']['text'])) {

            if (is_int($webhook['message']['text'])) {
                $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT');
            } else {
                $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT_NON_INT');
            }

        } else {
            $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT_NON_INT');
        }

    }
}
