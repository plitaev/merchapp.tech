<?php

namespace App\Actions\Core\Yookassa;

use YooKassa\Client;

class YookassaMakeRecurrent
{
    public function handle($data) {
        $client = new Client();
        $client->setAuth($data->bot->yookassa_shop_id, $data->bot->yookassa_shop_secret);

        $products = [];
        $products[]=["description" => $data->product->name, "quantity" => "1.00", "vat_code" => 1, "payment_mode" => "full_payment", "payment_subject" => "service", "amount" => ["value" => $data->prevous_pay->price, "currency" => "RUB", "vat_code" => 1, "payment_mode" => "full_payment", "payment_subject" => "service"]];

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
