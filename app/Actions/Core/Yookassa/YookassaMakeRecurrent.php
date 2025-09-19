<?php
namespace App\Actions\Core\Yookassa;

use App\Models\Core\BotUserBanSchedule;
use YooKassa\Client;

use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\Pay;

class YookassaMakeRecurrent
{
    public function handle($data) {

        $payCreateIntoBot = new PayCreateIntoBot();
        $payMakeSuccessful = new PayMakeSuccessful();

        $client = new Client();
        $client->setAuth($data->bot->yookassa_shop_id, $data->bot->yookassa_shop_secret);

        $products = [];
        $products[]=["description" => $data->product->name, "quantity" => "1.00", "vat_code" => 1, "payment_mode" => "full_payment", "payment_subject" => "service", "amount" => ["value" => $data->prevous_pay->price, "currency" => "RUB", "vat_code" => 1, "payment_mode" => "full_payment", "payment_subject" => "service"]];

        $additional_data = [];
        $additional_data['pay_system_id'] = $data->paysystem->id;

        $pay = $payCreateIntoBot->handle($data->bot_user, $data->product, $additional_data);
        if (!$pay) return ["new_pay_id" => NULL, "pay_system_responce" => '{"error":"prevous_pay_not_found"}'];

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
                'metadata' => ['orderNumber' => $pay->id]
            ),
            uniqid('', true)
        );

        if ($payment->status == "succeeded") {

            if (isset($payment->amount->value) && isset($payment->income_amount->value)) {
                $comission = $payment->amount->value-$payment->income_amount->value;
            } else {
                $comission = NULL;
            }

            $payMakeSuccessful->handle(json_encode($payment), $pay->id, $payment->id, $payment->payment_method->id, $comission);

            BotUserBanSchedule::where('bot_user_id', $data->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);
        }

        return $payment;
    }
}
