<?php

namespace App\Actions\Core\BotUser;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\BotUser;

class BotUserRepeatRecurrent
{
    public function handle(int $bot_user_id) {
        $botSendMessage = new BotSendMessage();

        $bot_user = BotUser::find($bot_user_id);
        $botSendMessage->handle($bot_user, 'BOT_PAYMENT_RECURRENT_FAIL');
    }
}
