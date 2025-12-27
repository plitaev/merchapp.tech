<?php

namespace App\Actions\Core\BotUser;

use Carbon\Carbon;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Product;

class BotUserRepeatRecurrent
{
    public function handle($data) {
        $botSendMessage = new BotSendMessage();
        $dateEnd = new DateEnd();
        $payCreateIntoBot = new PayCreateIntoBot();
        $payMakeSuccessful = new PayMakeSuccessful();

        $bot_user = BotUser::with('bot:id,recurrent_time')->find($data->bot_user_id);

        if ($bot_user->recurrent_repeat == 0) {

            $one_day_product = Product::where('bot_id', $bot_user->bot_id)->where('days', '1')->first();
            if ($one_day_product) {

                $additional_data = [];
                $additional_data['pay_system_id'] = 4;

                $new_pay = $payCreateIntoBot->handle($bot_user, $one_day_product, $additional_data);
                $payMakeSuccessful->handle('{"auto_generated_payment_finished": 1}', $new_pay->id, NULL, NULL, NULL);
                $dateEnd->handle($bot_user, 'Y-m-d');
            }

            $next_day = Carbon::now()->addDay()->format('Y-m-d');

            BotUserRecurrentSchedule::create(
                [
                    'bot_user_id' => $data->bot_user_id,
                    'prevous_pay_id' => $data->prevous_pay_id,
                    'recurrent_datetime' => $next_day.' '.$bot_user->bot->recurrent_time,
                    'run_status' => 0
                ]
            );

            $botSendMessage->handle($bot_user, 'BOT_PAYMENT_RECURRENT_FAIL');

            BotUser::where('id', $data->bot_user_id)->update(['recurrent_repeat' => 1]);
        }

    }
}
