<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;

class BotListenerPayCount
{
    public function handle($webhook, $bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        if (isset($webhook['message']['text'])) {

            if (is_int($webhook['message']['text'])) {
                $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT');
                $botUserSetListener->handle('listen_pay_count', 0, $bot_user->id);
            } else {
                $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT_NON_INT');
            }

        } else {
            $botSendMessage->handle($bot_user, 'SYS_USER_ENTERED_PAY_COUNT_NON_INT');
        }

    }
}
