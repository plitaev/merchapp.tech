<?php

namespace App\Actions\Core\Auto;

use YooKassa\Client;

use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentSchedulerProcess
{
    public function handle() {
        $res = BotUserRecurrentSchedule::with('prevous_pay:id,pay_system_payment_method_id,price')
            ->with('bot')
            ->with('bot_user:id,telegram_chat_id,first_name,last_name,email')
            ->with('product')
            ->select('bot_user_recurrent_schedules.id', 'prevous_pay_id', 'bot_user_id')
            ->where('recurrent_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->where('run_status', 0)
            ->get();

        foreach ($res as $data) {
            $client = new Client();
            $client->setAuth($data->bot->yookassa_shop_id, $data->bot->yookassa_shop_secret);

            $products = [];
            $products[]=["description" => $data->product->name, "quantity" => "1.00", "vat_code" => 1, "amount" => ["value" => $data->prevous_pay->price, "currency" => "RUB", "vat_code" => 1, "payment_mode" => "full_payment", "payment_subject" => "service"]];

            $payment = $client->createPayment(
                array(
                    'amount' => array(
                        'value' => $data->prevous_pay->price,
                        'currency' => 'RUB',
                    ),
                    'capture' => true,
                    'payment_method_id' => $data->prevous_pay->pay_system_payment_method_id,
                    'receipt' => array('customer' => array('full_name' => (isset($data->bot_user->first_name)?$data->bot_user->first_name:'').' '.(isset($data->bot_user->last_name)?$data->bot_user->last_name:''), 'email' => $data->bot_user->email), 'items' => $products),
                    'description' => $data->bot_user->telegram_chat_id,
                ),
                uniqid('', true)
            );

            return $payment;
        }

    }
}
