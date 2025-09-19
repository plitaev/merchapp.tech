<?php

namespace App\Actions\Core\Auto;

use YooKassa\Client;

use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentSchedulerProcess
{
    public function handle() {
        $res = BotUserRecurrentSchedule::with('prevous_pay:id,pay_system_payment_method_id')
            ->with('bot:id,yookassa_shop_id,yookassa_shop_secret,price')
            ->with('bot_user:id,telegram_chat_id')
            ->where('recurrent_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->where('run_status', 0)->get();

        foreach ($res as $data) {
            $client = new Client();
            $client->setAuth($data->bot->yookassa_shop_id, $data->bot->yookassa_shop_secret);

            $payment = $client->createPayment(
                array(
                    'amount' => array(
                        'value' => $data->prevous_pay->price,
                        'currency' => 'RUB',
                    ),
                    'capture' => true,
                    'payment_method_id' => $data->prevous_pay->price,
                    'description' => $data->bot_user->telegram_chat_id,
                ),
                uniqid('', true)
            );
        }

    }
}
