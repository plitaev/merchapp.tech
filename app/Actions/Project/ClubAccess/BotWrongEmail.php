<?php

namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;

class BotWrongEmail
{
    public function handle($bot_user) {
        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        if ($bot_user->sys_email_pay_not_found_first_status == 1) {
            $botSendMessage->handle($bot_user, 'SYS_EMAIL_PAY_NOT_FOUND_SECOND');
        } else {
            $botSendMessage->handle($bot_user, 'SYS_EMAIL_PAY_NOT_FOUND_FIRST');
        }

        $botUserSetListener->handle('listen_email', 1, $bot_user->id);
        die();
    }
}
