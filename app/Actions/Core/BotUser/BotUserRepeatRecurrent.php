<?php

namespace App\Actions\Core\BotUser;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\BotUser;
use App\Models\Core\Product;

class BotUserRepeatRecurrent
{
    public function handle(int $bot_user_id) {
        $botSendMessage = new BotSendMessage();
        $dateEnd = new DateEnd();
        $payCreateIntoBot = new PayCreateIntoBot();
        $payMakeSuccessful = new PayMakeSuccessful();

        $bot_user = BotUser::find($bot_user_id);
        $botSendMessage->handle($bot_user, 'BOT_PAYMENT_RECURRENT_FAIL');

        $one_day_product = Product::where('bot_id', $bot_user->bot_id)->where('days', '1')->first();
        if ($one_day_product) {

            $additional_data = [];
            $additional_data['pay_system_id'] = 4;

            $new_pay = $payCreateIntoBot->handle($bot_user, $one_day_product, $additional_data);
            $payMakeSuccessful->handle('{"auto_generated_payment_finished": 1}', $new_pay->id, NULL, NULL, NULL);
            $dateEnd->handle($bot_user, 'Y-m-d');
        }
    }
}
