<?php

namespace App\Actions\Core\BotUser;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\BotUser;
use App\Models\Core\Product;

class BotUserRepeatRecurrent
{
    public function handle(int $bot_user_id) {
        $botSendMessage = new BotSendMessage();

        $bot_user = BotUser::find($bot_user_id);
        $botSendMessage->handle($bot_user, 'BOT_PAYMENT_RECURRENT_FAIL');

        $one_day_product = Product::where('bot_id', $bot_user->bot_id)->where('days', '1')->first();
        if ($one_day_product) {

        }

    }
}
